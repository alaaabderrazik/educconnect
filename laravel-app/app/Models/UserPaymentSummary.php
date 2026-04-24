<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPaymentSummary extends Model
{
    protected $fillable = [
        'user_id', 'total_amount', 'paid_amount', 'remaining_amount', 
        'total_months', 'months_paid', 'months_remaining',
        'subscription_plan', 'monthly_fee', 'start_date', 'end_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
