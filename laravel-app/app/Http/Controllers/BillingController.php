<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentClass;
use App\Models\Subject;
use App\Models\Payment;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        // ==========================================
        // 1. STUDENTS (Grouped by Class)
        // ==========================================
        $classesQuery = StudentClass::with(['studentDetails' => function($q) use ($request) {
            $q->whereHas('user', function($uq) use ($request) {
                // Base role check
                $uq->whereHas('role', fn($r) => $r->where('name', 'student'));
                
                // Search filter
                if ($request->filled('student_search')) {
                    $search = $request->student_search;
                    $uq->where(function($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                    });
                }
                
                // Status Filter
                if ($request->filled('student_status')) {
                    $st = $request->student_status;
                    if ($st === 'paid') {
                        $uq->whereHas('paymentSummary', fn($pq) => $pq->where('remaining_amount', '<=', 0)->where('total_amount', '>', 0));
                    } elseif ($st === 'unpaid') {
                        $uq->where(function($pq) {
                            $pq->whereDoesntHave('paymentSummary')
                               ->orWhereHas('paymentSummary', fn($sq) => $sq->where('paid_amount', '<=', 0));
                        });
                    } elseif ($st === 'partial') {
                        $uq->whereHas('paymentSummary', fn($pq) => $pq->where('paid_amount', '>', 0)->whereColumn('paid_amount', '<', 'total_amount'));
                    }
                }
            })->with(['user.paymentSummary', 'user.payments']);
        }]);

        if ($request->filled('class_id')) {
            $classesQuery->where('id', $request->class_id);
        }

        $studentClasses = $classesQuery->get();

        // ==========================================
        // 2. TEACHERS
        // ==========================================
        $teachersQuery = User::whereHas('role', function($q) {
            $q->where('name', 'professor');
        })->with(['paymentSummary', 'payments', 'subjects']);

        if ($request->filled('teacher_search')) {
            $search = $request->teacher_search;
            $teachersQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('teacher_status')) {
            $st = $request->teacher_status;
            if ($st === 'paid') {
                $teachersQuery->whereHas('paymentSummary', fn($q) => $q->where('remaining_amount', '<=', 0)->where('total_amount', '>', 0));
            } elseif ($st === 'unpaid') {
                $teachersQuery->where(function($q) {
                    $q->whereDoesntHave('paymentSummary')
                      ->orWhereHas('paymentSummary', fn($sq) => $sq->where('paid_amount', '<=', 0));
                });
            } elseif ($st === 'partial') {
                $teachersQuery->whereHas('paymentSummary', fn($q) => $q->where('paid_amount', '>', 0)->whereColumn('paid_amount', '<', 'total_amount'));
            }
        }

        if ($request->filled('subject_id')) {
            $teachersQuery->whereHas('subjects', function($q) use ($request) {
                $q->where('id', $request->subject_id);
            });
        }

        $teachers = $teachersQuery->paginate(15, ['*'], 'teacher_page')->withQueryString();

        // Reference Data for Filters
        $classes = StudentClass::orderBy('class_name')->get();
        $subjects = Subject::orderBy('subject_name')->get();

        return view('dashboard.admin.billing.index', compact('studentClasses', 'teachers', 'classes', 'subjects'));
    }

    public function payCurrentMonth($id)
    {
        $user = User::findOrFail($id);
        $summary = $user->paymentSummary;
        
        if (!$summary || $summary->monthly_fee <= 0) {
            return back()->with('error', 'Cet utilisateur n\'a pas de plan d\'abonnement valide.');
        }

        $currentMonth = now()->translatedFormat('F Y');
        $rawMonth = now()->translatedFormat('F');
        
        $alreadyPaid = Payment::where('user_id', $user->id)
            ->where('month', 'like', '%' . $rawMonth . '%')
            ->whereYear('payment_date', now()->year)
            ->exists();
            
        if ($alreadyPaid) {
            return back()->with('error', 'Le mois actuel est déjà payé.');
        }

        Payment::create([
            'user_id' => $user->id,
            'amount' => $summary->monthly_fee,
            'type' => optional($user->role)->name ?? 'student',
            'status' => 'paid',
            'month' => ucfirst($currentMonth),
            'payment_date' => now(),
        ]);

        $summary->paid_amount += $summary->monthly_fee;
        $summary->remaining_amount = max(0, $summary->total_amount - $summary->paid_amount);
        $summary->months_paid += 1;
        $summary->months_remaining = max(0, $summary->total_months - $summary->months_paid);
        $summary->save();

        return back()->with('success', 'Paiement pour ' . $currentMonth . ' enregistré avec succès.');
    }

    public function paySpecificMonth(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $summary = $user->paymentSummary;
        $month = $request->input('month');

        if (!$summary || $summary->monthly_fee <= 0) {
            return back()->with('error', 'Plan d\'abonnement invalide.');
        }

        $alreadyPaid = Payment::where('user_id', $user->id)->where('month', $month)->exists();

        if ($alreadyPaid) {
            return back()->with('error', "Le mois de $month est déjà payé.");
        }

        Payment::create([
            'user_id' => $user->id,
            'amount' => $summary->monthly_fee,
            'type' => optional($user->role)->name ?? 'student',
            'status' => 'paid',
            'month' => $month,
            'payment_date' => now(),
        ]);

        $summary->paid_amount += $summary->monthly_fee;
        $summary->remaining_amount = max(0, $summary->total_amount - $summary->paid_amount);
        $summary->months_paid += 1;
        $summary->months_remaining = max(0, $summary->total_months - $summary->months_paid);
        $summary->save();

        return back()->with('success', "Paiement pour $month enregistré avec succès.");
    }

    public function revokeSpecificMonth(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $summary = $user->paymentSummary;
        $month = $request->input('month');

        $payment = Payment::where('user_id', $user->id)->where('month', $month)->first();

        if ($payment) {
            $payment->delete();
            
            if ($summary) {
                $summary->paid_amount = max(0, $summary->paid_amount - $payment->amount);
                $summary->remaining_amount = max(0, $summary->total_amount - $summary->paid_amount);
                $summary->months_paid = max(0, $summary->months_paid - 1);
                $summary->months_remaining = max(0, $summary->total_months - $summary->months_paid);
                $summary->save();
            }
            
            return back()->with('success', "Paiement pour $month annulé.");
        }
        
        return back();
    }
}
