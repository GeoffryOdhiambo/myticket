<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'category_id',
        'name',
        'slug',
        'ticket_prefix',
        'ticket_sequence',
        'description',
        'image_url',
        'venue',
        'location',
        'event_date',
        'start_time',
        'end_time',
        'contact_email',
        'contact_phone',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            if (empty($event->slug)) {
                $event->slug = static::uniqueSlug($event->name);
            }

            if (empty($event->ticket_prefix)) {
                $event->ticket_prefix = static::generateTicketPrefix($event->name);
            }
        });
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-".++$i;
        }

        return $slug;
    }

    /**
     * A short, human-scannable code unique per event (e.g. "NL" for
     * "Nairobi Live Sessions") used as the ticket number prefix, so
     * printed attendee lists sort/scan cleanly at the door.
     */
    public static function generateTicketPrefix(string $name): string
    {
        $words = array_values(array_filter(preg_split('/\s+/', preg_replace('/[^A-Za-z\s]/', '', $name))));

        $base = strtoupper(substr($words[0] ?? 'TK', 0, 1).substr($words[1] ?? $words[0] ?? 'TK', 0, 1));
        $base = str_pad(substr($base, 0, 2), 2, 'X');

        $prefix = $base;
        $attempt = 1;

        while (static::where('ticket_prefix', $prefix)->exists()) {
            $attempt++;
            $prefix = $attempt <= 9
                ? substr($base, 0, 1).$attempt
                : strtoupper(Str::random(2));
        }

        return $prefix;
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function ticketsQuery(): Builder
    {
        return Ticket::whereHas('orderItem.order', fn (Builder $query) => $query->where('event_id', $this->id));
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function getStartingPriceLabelAttribute(): string
    {
        $price = $this->ticketTypes->where('status', 'active')->min('price');

        return $price === null ? 'TBA' : Setting::current()->formatPrice((int) $price);
    }

    public function getDateTimeLabelAttribute(): string
    {
        return $this->event_date->format('D, d M Y').' · '.Carbon::parse($this->start_time)->format('g:i A');
    }
}
