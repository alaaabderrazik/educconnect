<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $conversations = $user->conversations()->with('latestMessage')->get();
        $users = User::where('id', '!=', $user->id)->orderBy('role_id')->orderBy('name')->get();

        if ($user->hasRole('admin')) {
            $classes = StudentClass::orderBy('class_name')->get();
        } elseif ($user->hasRole('professor')) {
            $classes = StudentClass::where('professor_responsible', $user->id)->orderBy('class_name')->get();
        } else {
            $classes = collect();
        }

        return view('messages.index', compact('conversations', 'users', 'classes'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $conversation = $user->conversations()->findOrFail($id);
        
        $messages = $conversation->messages()->with('sender')->orderBy('created_at', 'asc')->get();
        $users = User::where('id', '!=', $user->id)->orderBy('role_id')->orderBy('name')->get();
        
        // Update last read at
        $user->conversations()->updateExistingPivot($id, ['last_read_at' => now()]);

        return view('messages.show', compact('conversation', 'messages', 'users'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'message_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,png,jpeg,doc,docx|max:5120',
        ]);

        if (!$request->message_text && !$request->hasFile('file')) {
            return back()->with('error', 'Message cannot be empty.');
        }

        $user = Auth::user();
        $conversation = $user->conversations()->findOrFail($id);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('messages', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message_text' => $request->message_text,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        $notificationData = [];
        $now = now();
        foreach ($conversation->users as $member) {
            if ($member->id !== $user->id) {
                $notificationData[] = [
                    'user_id' => $member->id,
                    'title' => 'Nouveau message',
                    'message' => 'Vous avez reçu un message de ' . $user->name,
                    'type' => 'message',
                    'link' => route('messages.show', $conversation->id),
                    'is_read' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        if (!empty($notificationData)) {
            \App\Models\Notification::insert($notificationData);
        }

        return back()->with('success', 'Message sent.');
    }

    public function startPrivate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,png,jpeg,doc,docx|max:5120',
        ]);

        if (!$request->message_text && !$request->hasFile('file')) {
            return back()->with('error', 'Message cannot be empty.');
        }

        $user1 = Auth::user();
        $user2 = User::findOrFail($request->user_id);

        // Check if there is already a private conversation
        $conversation = $user1->conversations()
            ->where('type', 'private')
            ->whereHas('users', function ($q) use ($user2) {
                $q->where('users.id', $user2->id);
            })->first();

        if (!$conversation) {
            $conversation = Conversation::create(['type' => 'private']);
            $conversation->users()->attach([$user1->id, $user2->id]);
        }

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('messages', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'message_text' => $request->message_text,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        \App\Models\Notification::create([
            'user_id' => $user2->id,
            'title' => 'Nouveau message',
            'message' => 'Vous avez reçu un message de ' . $user1->name,
            'type' => 'message',
            'link' => route('messages.show', $conversation->id),
            'is_read' => false,
        ]);

        return redirect()->route('messages.show', $conversation->id);
    }

    // Teacher group message or Admin broadcast
    public function startGroup(Request $request)
    {
        $user = Auth::user();

        // Admin can broadcast to 'teachers', 'students'
        // Teacher can broadcast to their student_class
        $request->validate([
            'target_type' => 'required|string', // e.g. 'class', 'all_students', 'all_teachers'
            'target_id' => 'nullable|integer',
            'message_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,png,jpeg,doc,docx|max:5120',
        ]);

        if (!$request->message_text && !$request->hasFile('file')) {
            return back()->with('error', 'Message cannot be empty.');
        }

        $userIds = [];
        $groupName = 'Group Chat';

        $classId = null;

        if ($user->hasRole('admin')) {
            if ($request->target_type === 'all_students') {
                $userIds = User::whereHas('role', fn($q) => $q->where('name', 'student'))->pluck('id')->toArray();
                $groupName = 'Broadcast: All Students';
            } elseif ($request->target_type === 'all_teachers') {
                $userIds = User::whereHas('role', fn($q) => $q->where('name', 'professor'))->pluck('id')->toArray();
                $groupName = 'Broadcast: All Teachers';
            } elseif ($request->target_type === 'class') {
                $class = StudentClass::findOrFail($request->target_id);
                $userIds = User::whereHas('role', fn($q) => $q->where('name', 'student'))
                    ->whereHas('studentDetails', fn($q) => $q->where('student_class_id', $class->id))
                    ->pluck('id')->toArray();
                $groupName = 'Classe: ' . $class->class_name;
                $classId = $class->id;
            }
        } elseif ($user->hasRole('professor')) {
            if ($request->target_type === 'class') {
                $class = StudentClass::where('professor_responsible', $user->id)->findOrFail($request->target_id);
                $userIds = User::whereHas('role', fn($q) => $q->where('name', 'student'))
                    ->whereHas('studentDetails', fn($q) => $q->where('student_class_id', $class->id))
                    ->pluck('id')->toArray();
                $groupName = 'Classe: ' . $class->class_name;
                $classId = $class->id;
            }
        }

        array_push($userIds, $user->id); // include sender
        $userIds = array_unique($userIds);

        if (count($userIds) <= 1) {
            return back()->with('error', 'No recipients found.');
        }

        $conversation = Conversation::create([
            'type' => 'group',
            'name' => $groupName,
            'class_id' => $classId,
        ]);

        $conversation->users()->attach($userIds);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('messages', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message_text' => $request->message_text,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        $notificationData = [];
        $now = now();
        foreach ($userIds as $memberId) {
            if ($memberId !== $user->id) {
                $notificationData[] = [
                    'user_id' => $memberId,
                    'title' => 'Nouvelle diffusion',
                    'message' => 'Message de groupe de ' . $user->name,
                    'type' => 'message',
                    'link' => route('messages.show', $conversation->id),
                    'is_read' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        if (!empty($notificationData)) {
            \App\Models\Notification::insert($notificationData);
        }

        return redirect()->route('messages.show', $conversation->id);
    }
    
    public function downloadFile(Message $message)
    {
        $user = Auth::user();
        if (!$user->conversations->contains($message->conversation_id)) {
            abort(403, 'Unauthorized access to this file.');
        }

        if (!$message->file_path) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($message->file_path, $message->file_name ?? 'download');
    }

    /**
     * AJAX endpoint: search users for the "New Message" picker.
     * GET /dashboard/messages/search-users?query=&role=
     */
    public function searchUsers(Request $request)
    {
        $authUser = Auth::user();
        $query    = trim($request->input('query', ''));
        $role     = $request->input('role', 'all'); // all | student | professor | admin

        $users = User::with(['role', 'studentDetails.studentClass', 'professorDetails.subject'])
            ->where('id', '!=', $authUser->id)
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $like = '%' . $query . '%';
                    $sub->where('name',       'like', $like)
                        ->orWhere('first_name', 'like', $like)
                        ->orWhere('last_name',  'like', $like)
                        ->orWhere('username',   'like', $like)
                        ->orWhere('email',      'like', $like);
                });
            })
            ->when($role !== 'all', function ($q) use ($role) {
                // Map frontend role slug → DB role name
                $roleMap = [
                    'student'   => 'student',
                    'professor' => 'professor',
                    'admin'     => 'admin',
                ];
                $roleName = $roleMap[$role] ?? null;
                if ($roleName) {
                    $q->whereHas('role', fn ($r) => $r->where('name', $roleName));
                }
            })
            ->orderBy('name')
            ->limit(25)
            ->get()
            ->map(function ($u) {
                $roleName = optional($u->role)->name ?? 'user';

                // Extra info depending on role
                $info = null;
                if ($roleName === 'student' && $u->studentDetails) {
                    $info = optional(optional($u->studentDetails)->studentClass)->class_name
                         ?? $u->studentDetails->filiere
                         ?? null;
                } elseif ($roleName === 'professor' && $u->professorDetails) {
                    $info = optional(optional($u->professorDetails)->subject)->name
                         ?? $u->professorDetails->specialty
                         ?? null;
                }

                return [
                    'id'     => $u->id,
                    'name'   => $u->name,
                    'email'  => $u->email,
                    'role'   => $roleName,
                    'info'   => $info,
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=random',
                ];
            });

        return response()->json($users);
    }
}
