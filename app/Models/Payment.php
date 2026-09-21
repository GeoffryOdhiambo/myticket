<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'provider',
        'amount',
        'status',
        'provider_reference',
        'checkout_request_id',
        'raw_response',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'raw_response' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
