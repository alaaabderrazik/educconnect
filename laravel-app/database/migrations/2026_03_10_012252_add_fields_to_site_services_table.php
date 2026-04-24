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
        Schema::table('site_services', function (Blueprint $table) {
            $table->string('image')->nullable()->after('icon');
            $table->string('related_course')->nullable()->after('image');
            $table->string('professor_name')->nullable()->after('related_course');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_services', function (Blueprint $table) {
            $table->dropColumn(['image', 'related_course', 'professor_name']);
        });
    }
};
