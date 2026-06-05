<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeBankDetail extends Model
{
    protected $fillable = [
        'user_id', 'payment_type', 'bank_name', 'account_number',
        'account_holder_name', 'mobile_provider', 'mobile_number',
        'card_last_four', 'card_holder_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
