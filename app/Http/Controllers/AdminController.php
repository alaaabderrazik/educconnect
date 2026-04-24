<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\AcademicLevel;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\SiteSetting;
use App\Models\SiteService;
use App\Models\Course;
use App\Models\TeamMember;
use App\Models\FAQ;
use App\Models\Testimonial;
use App\Models\StudentClass;
use App\Models\Permission;
use App\Models\Page;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'students_count' => User::whereHas('role', function($q){ $q->where('name', 'student'); })->count(),
            'professors_count' => User::whereHas('role', function($q){ $q->where('name', 'professor'); })->count(),
            'courses_count' => Course::count(),
            'recent_notifications' => Notification::where('user_id', auth()->id())->latest()->take(5)->get(),
            'levels_labels' => AcademicLevel::pluck('name'),
            'levels_data' => AcademicLevel::withCount('studentDetails')->pluck('student_details_count'),
        ];
        
        return view('dashboard.admin.index', compact('stats'));
    }

    // --- Student Management ---
    public function students(Request $request)
    {
        $classId = $request->get('class_id');
        $profileStatus = $request->get('profile_status');
        
        $query = User::whereHas('role', function($q){ $q->where('name', 'student'); })
            ->with(['studentDetails.academicLevel', 'studentDetails.studentClass']);

        if ($classId) {
            $query->whereHas('studentDetails', function($q) use ($classId) {
                $q->where('student_class_id', $classId);
            });
        }

        if ($profileStatus !== null && $profileStatus !== '') {
            $query->where('profile_completed', (bool)$profileStatus);
        }

        $students = $query->latest()->paginate(10);
            
        $levels = AcademicLevel::all();
        $classes = StudentClass::all();
        $years = AcademicYear::all();
            
        return view('dashboard.admin.students', compact('students', 'levels', 'classes', 'years', 'classId', 'profileStatus'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users',
            'password' => 'nullable|min:8',
            'username' => 'nullable|unique:users',
            'profile_photo' => 'nullable|image|max:2048',
            'student_class_id' => 'required|exists:student_classes,id',
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Automatic Account Generation
        $firstName = strtolower(trim($request->first_name));
        $lastName = strtolower(trim($request->last_name));
        $generatedUsername = $request->username ?: str_replace(' ', '', $firstName . $lastName);
        $generatedPassword = $request->password ?: $generatedUsername . '@2026';
        
        // Ensure unique username
        $baseUsername = $generatedUsername;
        $counter = 1;
        while (User::where('username', $generatedUsername)->exists()) {
            $generatedUsername = $baseUsername . $counter;
            $counter++;
        }

        $role = Role::where('name', 'student')->first();
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $generatedUsername,
            'email' => $request->email,
            'password' => Hash::make($generatedPassword),
            'role_id' => $role->id,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'city' => $request->city,
            'profile_photo' => $photoPath,
            'status' => $request->status ?? 'active',
            'profile_completed' => false,
        ]);

        $user->studentDetails()->create([
            'student_code' => $request->student_code ?: 'STD-' . strtoupper(Str::random(6)),
            'filiere' => $request->filiere,
            'registration_date' => $request->registration_date ?? now(),
            'academic_level_id' => $request->academic_level_id,
            'student_class_id' => $request->student_class_id,
            'academic_year_id' => $request->academic_year_id,
        ]);

        return back()->with('success', "Étudiant ajouté avec succès ! Identifiants : {$generatedUsername} / {$generatedPassword}");
    }

    public function updateStudent(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'nullable|unique:users,username,' . $user->id,
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $photoPath;
        }

        $user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'city' => $request->city,
            'status' => $request->status ?? 'active',
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->studentDetails()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'student_code' => $request->student_code,
                'filiere' => $request->filiere,
                'registration_date' => $request->registration_date ?? $user->studentDetails->registration_date ?? now(),
                'academic_level_id' => $request->academic_level_id,
                'student_class_id' => $request->student_class_id,
                'academic_year_id' => $request->academic_year_id,
            ]
        );

        return back()->with('success', 'Étudiant mis à jour avec succès !');
    }

    // --- Professor Management ---
    public function professors(Request $request)
    {
        $query = User::whereHas('role', function($q){ $q->where('name', 'professor'); })
            ->with(['professorDetails', 'subjects', 'assignedClasses'])
            ->withCount(['coursesTaught', 'assignedClasses']);

        if ($request->has('profile_status')) {
            $status = $request->profile_status === 'completed';
            $query->where('profile_completed', $status);
        }

        $professors = $query->latest()->get();
            
        $subjects = Subject::all();
        $classes = StudentClass::all();
        $profile_status = $request->profile_status;
            
        return view('dashboard.admin.professors', compact('professors', 'subjects', 'classes', 'profile_status'));
    }

    public function storeProfessor(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        // Auto-generate username
        $baseUsername = strtolower(str_replace(' ', '', $request->first_name . $request->last_name));
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        // Auto-generate password
        $password = $username . '@2026';

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $role = Role::where('name', 'professor')->first();
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $username,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role_id' => $role->id,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'profile_photo' => $photoPath,
            'status' => $request->status ?? 'active',
            'profile_completed' => false,
        ]);

        $user->professorDetails()->create([
            'professor_code' => $request->professor_code ?? 'PROF-' . strtoupper(Str::random(6)),
            'specialty' => $request->specialty,
            'hire_date' => $request->hire_date ?? now(),
            'office' => $request->office,
        ]);

        if ($request->has('classes')) {
            \App\Models\StudentClass::whereIn('id', $request->classes)->update(['professor_responsible' => $user->id]);
        }
        
        if ($request->has('subjects')) {
            \App\Models\Subject::whereIn('id', $request->subjects)->update(['professor_id' => $user->id]);
        }

        return back()->with('success', "Professeur ajouté avec succès ! Identifiants : Username: $username, Password: $password");
    }

    public function updateProfessor(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $photoPath;
        }

        $user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'status' => $request->status ?? 'active',
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->professorDetails()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'professor_code' => $request->professor_code,
                'specialty' => $request->specialty,
                'hire_date' => $request->hire_date ?? $user->professorDetails->hire_date ?? now(),
                'subject_id' => $request->subject_id,
                'office' => $request->office,
            ]
        );

        if ($request->has('classes')) {
            // Reset previous assignments for this professor to allow clean "sync"
            \App\Models\StudentClass::where('professor_responsible', $user->id)->update(['professor_responsible' => null]);
            \App\Models\StudentClass::whereIn('id', $request->classes)->update(['professor_responsible' => $user->id]);
        }
        
        if ($request->has('subjects')) {
            // Reset previous assignments
            \App\Models\Subject::where('professor_id', $user->id)->update(['professor_id' => null]);
            \App\Models\Subject::whereIn('id', $request->subjects)->update(['professor_id' => $user->id]);
        }

        return back()->with('success', 'Professeur mis à jour avec succès !');
    }

    // --- Administrator Management ---
    public function admins()
    {
        $admins = User::whereHas('role', function($q){ $q->where('name', 'admin'); })
            ->with(['administratorDetails', 'role.permissions'])
            ->latest()
            ->get();
        $permissions = Permission::all()->groupBy('group');
        return view('dashboard.admin.admins', compact('admins', 'permissions'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'nullable|unique:users',
            'admin_type' => 'required',
            'password' => 'required|min:8',
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $role = Role::where('name', 'admin')->first();
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
            'admin_type' => $request->admin_type,
            'profile_photo' => $photoPath,
            'status' => 'active',
        ]);

        $user->administratorDetails()->create(['user_id' => $user->id]);

        if ($request->has('permissions')) {
            $user->role->permissions()->sync($request->permissions);
        }

        return back()->with('success', 'Administrateur ajouté avec succès !');
    }

    public function updateAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'nullable|unique:users,username,' . $user->id,
            'admin_type' => 'required',
            'password' => 'nullable|min:8',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $photoPath;
        }

        $user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'admin_type' => $request->admin_type,
            'status' => $request->status ?? 'active',
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Handle permissions
        if ($request->has('permissions')) {
            $user->role->permissions()->sync($request->permissions);
        }

        return back()->with('success', 'Administrateur mis à jour avec succès !');
    }

    public function levels()
    {
        $levels = AcademicLevel::withCount(['studentClasses', 'subjects'])
            ->with('academicYear')
            ->get();
        return view('dashboard.admin.levels', compact('levels'));
    }

    public function storeLevel(Request $request)
    {
        $request->validate([
            'name' => 'required', 
            'code' => 'required|unique:academic_levels',
            'academic_year_id' => 'required|exists:academic_years,id'
        ]);
        AcademicLevel::create($request->all());
        return back()->with('success', 'Niveau ajouté !');
    }

    // --- Classes Management ---
    public function classes(Request $request)
    {
        $levelId = $request->get('level_id');
        
        $query = StudentClass::with(['academicLevel', 'academicYear', 'responsibleProfessor'])
            ->withCount('studentDetails');
            
        if ($levelId) {
            $query->where('level_id', $levelId);
        }
        
        $classes = $query->get();
        $levels = AcademicLevel::all();
        $professors = User::whereHas('role', function($q){ $q->where('name', 'professor'); })->get();
        $activeYear = AcademicYear::where('status', 'active')->first();
        
        return view('dashboard.admin.classes', compact('classes', 'levels', 'professors', 'activeYear', 'levelId'));
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'level_id' => 'required|exists:academic_levels,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'professor_responsible' => 'nullable|exists:users,id',
            'capacity' => 'nullable|integer',
            'room' => 'nullable|string',
        ]);

        StudentClass::create($request->all());
        return back()->with('success', 'Classe créée avec succès !');
    }

    // --- Subjects Management ---
    public function subjects()
    {
        $levels = AcademicLevel::all();
        $professors = User::whereHas('role', function($q){ $q->where('name', 'professor'); })->get();
        $subjects = Subject::with(['academicLevel', 'professor'])->latest()->get();
        
        return view('dashboard.admin.subjects', compact('levels', 'professors', 'subjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'code' => 'required|unique:subjects',
            'academic_level_id' => 'required|exists:academic_levels,id',
            'professor_id' => 'nullable|exists:users,id',
            'hours' => 'required|integer|min:0',
            'coefficient' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);
        
        Subject::create($request->all());
        return back()->with('success', 'Matière ajoutée !');
    }

    public function years()
    {
        $years = AcademicYear::withCount(['academicLevels', 'studentClasses'])->latest()->get();
        // Calculate students count per year
        foreach ($years as $year) {
            $year->students_count = User::whereHas('studentDetails', function ($q) use ($year) {
                $q->where('academic_year_id', $year->id);
            })->count();
        }
        return view('dashboard.admin.years', compact('years'));
    }

    public function storeYear(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        $data = $request->all();
        
        if ($request->has('is_active') && $request->is_active) {
            // Deactivate all others
            AcademicYear::query()->update(['is_active' => false, 'status' => 'closed']);
            $data['status'] = 'active';
            $data['is_active'] = true;
        } else {
            $data['status'] = 'closed';
            $data['is_active'] = false;
        }

        AcademicYear::create($data);
        return back()->with('success', 'Année scolaire ajoutée !');
    }

    public function activateYear($id)
    {
        AcademicYear::query()->update(['is_active' => false, 'status' => 'closed']);
        
        $year = AcademicYear::findOrFail($id);
        $year->update(['is_active' => true, 'status' => 'active']);

        return back()->with('success', 'Année scolaire activée avec succès !');
    }

    public function closeYear($id)
    {
        $year = AcademicYear::findOrFail($id);
        $year->update(['is_active' => false, 'status' => 'closed']);

        return back()->with('success', 'Année scolaire clôturée avec succès !');
    }

    // --- Site Management ---
    public function services()
    {
        $services = SiteService::all();
        return view('dashboard.admin.services', compact('services'));
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'icon' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        SiteService::create($data);
        return back()->with('success', 'Service ajouté avec succès !');
    }

    public function updateService(Request $request, $id)
    {
        $service = SiteService::findOrFail($id);
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'icon' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);
        return back()->with('success', 'Service mis à jour avec succès !');
    }

    public function pages()
    {
        $pages = Page::all()->groupBy('page_name');
        return view('dashboard.admin.pages.index', compact('pages'));
    }

    public function editPageHome()
    {
        $contents = Page::where('page_name', 'home')->get()->keyBy('section');
        return view('dashboard.admin.pages.home', compact('contents'));
    }

    public function editPageAbout()
    {
        $contents = Page::where('page_name', 'about')->get()->keyBy('section');
        return view('dashboard.admin.pages.about', compact('contents'));
    }

    public function editPageServices()
    {
        $services = SiteService::all();
        $page = Page::where('page_name', 'services')->where('section', 'header')->first();
        return view('dashboard.admin.pages.services', compact('services', 'page'));
    }

    public function editPageContact()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        $page = Page::where('page_name', 'contact')->where('section', 'header')->first();
        return view('dashboard.admin.pages.contact', compact('settings', 'page'));
    }

    public function updatePageSection(Request $request)
    {
        $id = $request->input('id');
        $page = Page::findOrFail($id);
        
        $data = $request->only(['title', 'content']);
        
        if ($request->hasFile('image')) {
            if ($page->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($page->image);
            }
            $data['image'] = $request->file('image')->store('pages', 'public');
        }
        
        if ($request->input('clear_image') == '1') {
            if ($page->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($page->image);
            }
            $data['image'] = null;
        }

        $page->update($data);
        return back()->with('success', 'Section mise à jour !');
    }

    public function uploadPageImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:5120', 'page_id' => 'required|exists:pages,id']);
        $page = Page::findOrFail($request->page_id);
        if ($page->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($page->image);
        }
        $path = $request->file('image')->store('pages', 'public');
        $page->update(['image' => $path]);
        return response()->json(['success' => true, 'url' => asset('storage/' . $path), 'path' => $path]);
    }

    public function deletePageImage(Request $request)
    {
        $page = Page::findOrFail($request->page_id);
        if ($page->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($page->image);
            $page->update(['image' => null]);
        }
        return response()->json(['success' => true]);
    }

    public function settings()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('dashboard.admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except(['_token', 'site_logo', 'site_favicon', '_method']);
        
        if ($request->hasFile('site_logo')) {
            $data['site_logo'] = $request->file('site_logo')->store('settings', 'public');
        }
        if ($request->hasFile('site_favicon')) {
            $data['site_favicon'] = $request->file('site_favicon')->store('settings', 'public');
        }

        foreach($data as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        
        return back()->with('success', 'Paramètres généraux mis à jour !');
    }

    // --- New Entity CRUDs ---
    public function team()
    {
        $members = TeamMember::orderBy('order')->get();
        return view('dashboard.admin.team', compact('members'));
    }

    public function storeTeamMember(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'position' => 'required',
            'photo_path' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = $request->file('photo_path')->store('team', 'public');
        }

        TeamMember::create($data);
        return back()->with('success', 'Membre de l\'équipe ajouté !');
    }

    public function updateTeamMember(Request $request, $id)
    {
        $member = TeamMember::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'position' => 'required',
            'photo_path' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = $request->file('photo_path')->store('team', 'public');
        }

        $member->update($data);
        return back()->with('success', 'Membre de l\'équipe mis à jour !');
    }

    public function faqs()
    {
        $faqs = FAQ::orderBy('order')->get();
        return view('dashboard.admin.faqs', compact('faqs'));
    }

    public function storeFAQ(Request $request)
    {
        $request->validate(['question' => 'required', 'answer' => 'required']);
        FAQ::create($request->all());
        return back()->with('success', 'FAQ ajoutée !');
    }

    public function updateFAQ(Request $request, $id)
    {
        $faq = FAQ::findOrFail($id);
        $request->validate(['question' => 'required', 'answer' => 'required']);
        $faq->update($request->all());
        return back()->with('success', 'FAQ mise à jour !');
    }

    public function testimonials()
    {
        $testimonials = Testimonial::latest()->get();
        return view('dashboard.admin.testimonials', compact('testimonials'));
    }

    public function storeTestimonial(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'position' => 'required',
            'content' => 'required',
            'photo_path' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = $request->file('photo_path')->store('testimonials', 'public');
        }

        Testimonial::create($data);
        return back()->with('success', 'Témoignage ajouté !');
    }

    public function updateTestimonial(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'position' => 'required',
            'content' => 'required',
            'photo_path' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = $request->file('photo_path')->store('testimonials', 'public');
        }

        $testimonial->update($data);
        return back()->with('success', 'Témoignage mis à jour !');
    }

    public function roles()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy('group');
        return view('dashboard.admin.roles', compact('roles', 'permissions'));
    }

    public function updateRolePermissions(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissionIds = $request->input('permissions', []);
        $role->permissions()->sync($permissionIds);
        return response()->json(['success' => true, 'message' => 'Permissions mises à jour !']);
    }

    public function logs()
    {
        return view('dashboard.admin.logs'); // Static for now or implement log model
    }

    // Generic Delete
    public function destroy($type, $id)
    {
        $model = match($type) {
            'user' => User::class,
            'level' => AcademicLevel::class,
            'class' => StudentClass::class,
            'subject' => Subject::class,
            'year' => AcademicYear::class,
            'service' => SiteService::class,
            'team' => TeamMember::class,
            'faq' => FAQ::class,
            'testimonial' => Testimonial::class,
            default => null
        };

        if ($model) {
            $model::find($id)->delete();
            return back()->with('success', 'Élément supprimé avec succès !');
        }

        return back()->with('error', 'Erreur lors de la suppression.');
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('dashboard.admin.notifications', compact('notifications'));
    }
}
