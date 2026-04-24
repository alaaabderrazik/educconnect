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
        Schema::table('user_payment_summaries', function (Blueprint $table) {
            $table->string('subscription_plan')->nullable();
            $table->decimal('monthly_fee', 10, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_payment_summaries', function (Blueprint $table) {
            $table->dropColumn(['subscription_plan', 'monthly_fee', 'start_date', 'end_date']);
        });
    }
};
