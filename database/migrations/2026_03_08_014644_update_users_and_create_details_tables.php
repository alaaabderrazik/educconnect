<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('username')->unique()->nullable()->after('last_name');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('city')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->string('admin_type')->nullable(); // super, academic, live_courses, content
        });

        Schema::create('student_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('student_code')->unique()->nullable();
            $table->string('filiere')->nullable();
            $table->date('registration_date')->nullable();
            $table->foreignId('academic_level_id')->nullable()->constrained('academic_levels')->onDelete('set null');
            $table->foreignId('student_class_id')->nullable()->constrained('student_classes')->onDelete('set null');
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('professor_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('professor_code')->unique()->nullable();
            $table->string('specialty')->nullable();
            $table->date('hire_date')->nullable();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professor_details');
        Schema::dropIfExists('student_details');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'username', 'gender', 'dob', 
                'profile_photo', 'city', 'status', 'admin_type'
            ]);
        });
    }
};
