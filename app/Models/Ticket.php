<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'ticket_number',
        'uuid',
        'checked_in',
        'checked_in_at',
        'checked_in_by',
    ];

    protected function casts(): array
    {
        return [
            'checked_in' => 'boolean',
            'checked_in_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'ticket_number';
    }

    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket) {
            if (empty($ticket->uuid)) {
                $ticket->uuid = (string) Str::uuid();
            }

            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = static::generateTicketNumber();
            }
        });
    }

    public static function generateTicketNumber(): string
    {
        do {
            $number = 'TIKO-'.strtoupper(Str::random(8));
        } while (static::where('ticket_number', $number)->exists());

        return $number;
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function order(): HasOneThrough
    {
        return $this->hasOneThrough(
            Order::class,
            OrderItem::class,
            'id',
            'id',
            'order_item_id',
            'order_id'
        );
    }

    public function checkIn(string $checkedInBy): void
    {
        $this->update([
            'checked_in' => true,
            'checked_in_at' => now(),
            'checked_in_by' => $checkedInBy,
        ]);
    }
}
