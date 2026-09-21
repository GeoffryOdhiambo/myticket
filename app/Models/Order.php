<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'event_id',
        'customer_name',
        'customer_whatsapp',
        'customer_email',
        'subtotal',
        'total',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'total' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        do {
            $number = 'TIKO-ORD-' . strtoupper(Str::random(6));
        } while (static::where('order_number', $number)->exists());

        return $number;
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid', 'paid_at' => now()]);
    }

    public function getTotalLabelAttribute(): string
    {
        return Setting::current()->formatPrice($this->total);
    }
}
