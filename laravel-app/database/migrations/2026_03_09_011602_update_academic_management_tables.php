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
        // Add status to academic_years (if using enum or boolean; we already have is_active, but let's keep consistency)
        Schema::table('academic_years', function (Blueprint $table) {
            if (!Schema::hasColumn('academic_years', 'status')) {
                $table->string('status')->default('active')->after('end_date');
            }
        });

        Schema::table('academic_levels', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('cascade')->after('id');
        });

        Schema::table('student_classes', function (Blueprint $table) {
            $table->renameColumn('name', 'class_name');
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('cascade')->after('id');
            $table->foreignId('level_id')->nullable()->constrained('academic_levels')->onDelete('cascade')->after('academic_year_id');
            $table->foreignId('professor_responsible')->nullable()->constrained('users')->onDelete('set null')->after('capacity');
            $table->string('room')->nullable()->after('professor_responsible');
            $table->integer('number_of_students')->default(0)->after('room');
            if (Schema::hasColumn('student_classes', 'year')) {
                $table->dropColumn('year');
            }
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->renameColumn('name', 'subject_name');
            $table->text('description')->nullable()->after('subject_name');
            $table->foreignId('professor_id')->nullable()->constrained('users')->onDelete('set null')->after('academic_level_id');
            $table->integer('hours')->default(0)->after('professor_id');
            $table->integer('coefficient')->default(1)->after('hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->renameColumn('subject_name', 'name');
            $table->dropForeign(['professor_id']);
            $table->dropColumn(['description', 'professor_id', 'hours', 'coefficient']);
        });

        Schema::table('student_classes', function (Blueprint $table) {
            $table->renameColumn('class_name', 'name');
            $table->string('year')->nullable();
            $table->dropForeign(['academic_year_id']);
            $table->dropForeign(['level_id']);
            $table->dropForeign(['professor_responsible']);
            $table->dropColumn(['academic_year_id', 'level_id', 'professor_responsible', 'room', 'number_of_students']);
        });

        Schema::table('academic_levels', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn(['description', 'academic_year_id']);
        });
        
        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
