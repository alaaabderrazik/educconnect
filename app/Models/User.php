<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'role_id',
        'phone',
        'address',
        'gender',
        'dob',
        'profile_photo',
        'city',
        'status',
        'admin_type',
        'profile_completed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function studentDetails()
    {
        return $this->hasOne(StudentDetail::class);
    }

    // A User (Parent) has many StudentDetail records
    public function childrenDetails()
    {
        return $this->hasMany(StudentDetail::class, 'parent_id');
    }

    public function professorDetails()
    {
        return $this->hasOne(ProfessorDetail::class);
    }

    public function administratorDetails()
    {
        return $this->hasOne(AdministratorDetail::class);
    }

    public function getAgeAttribute()
    {
        if ($this->dob) {
            return \Carbon\Carbon::parse($this->dob)->age;
        }
        return null;
    }

    // A User (Professor) has many courses they teach
    public function coursesTaught()
    {
        return $this->hasMany(Course::class, 'professor_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'professor_id');
    }

    public function assignedClasses()
    {
        return $this->hasMany(StudentClass::class, 'professor_responsible');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class, 'user_id')->where('is_read', false);
    }

    // Relationships for Online Courses
    public function onlineCoursesAsProfessor()
    {
        return $this->hasMany(OnlineCourse::class, 'professor_id');
    }

    public function onlineCoursesAsStudent()
    {
        // This assumes the student is linked to a class and we fetch courses for that class
        // For now, let's keep it simple and filter in the controller, 
        // but we can add a helper if needed.
        return null; 
    }

    // A User (Student) has many enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function paymentSummary()
    {
        return $this->hasOne(UserPaymentSummary::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Messaging relationship
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class)->withPivot('last_read_at');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
