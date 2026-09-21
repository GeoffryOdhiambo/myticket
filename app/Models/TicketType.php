<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quantity',
        'quantity_sold',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'quantity' => 'integer',
            'quantity_sold' => 'integer',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isUnlimited(): bool
    {
        return $this->quantity === null;
    }

    public function getAvailableQuantityAttribute(): ?int
    {
        return $this->isUnlimited() ? null : max(0, $this->quantity - $this->quantity_sold);
    }

    public function isSoldOut(): bool
    {
        return ! $this->isUnlimited() && $this->available_quantity <= 0;
    }

    public function canPurchase(int $quantity): bool
    {
        if (! $this->isActive()) {
            return false;
        }

        return $this->isUnlimited() || $this->available_quantity >= $quantity;
    }

    public function getPriceLabelAttribute(): string
    {
        return Setting::current()->formatPrice($this->price);
    }
}
