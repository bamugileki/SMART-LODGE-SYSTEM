<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    protected $fillable = [
        'transaction_id',
        'receipt_number',
        'booking_id',
        'amount',
        'method',
        'status',
        'notes',
        'paid_at',
        'refunded_at',
        'processed_by',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'refunded_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            if (!$payment->receipt_number) {
                $payment->receipt_number = static::generateReceiptNumber();
            }
        });
    }

    public static function generateReceiptNumber(): string
    {
        $prefix = 'RCP-' . date('Ymd');
        $last = static::where('receipt_number', 'like', "{$prefix}-%")
            ->orderBy('receipt_number', 'desc')
            ->value('receipt_number');

        $seq = $last ? (int) Str::afterLast($last, '-') + 1 : 1;
        return "{$prefix}-" . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at')->where('status', 'paid');
    }
}
