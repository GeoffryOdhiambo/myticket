<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'category_id',
        'name',
        'slug',
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
        });
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-" . ++$i;
        }

        return $slug;
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

    public function ticketsQuery(): \Illuminate\Database\Eloquent\Builder
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
        return $this->event_date->format('D, d M Y') . ' · ' . \Illuminate\Support\Carbon::parse($this->start_time)->format('g:i A');
    }
}
