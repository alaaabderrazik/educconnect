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
        Schema::rename('notifications_log', 'notifications');
        Schema::table('notifications', function (Blueprint $table) {
            $table->renameColumn('body', 'message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->renameColumn('message', 'body');
        });
        Schema::rename('notifications', 'notifications_log');
    }
};
