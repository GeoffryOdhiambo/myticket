<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_name',
        'logo_path',
        'support_email',
        'support_phone',
        'platform_fee_percent',
        'currency',
        'payment_driver',
        'require_organizer_approval',
    ];

    protected function casts(): array
    {
        return [
            'platform_fee_percent' => 'integer',
            'require_organizer_approval' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('tiko.settings'));
    }

    public static function current(): self
    {
        return Cache::rememberForever('tiko.settings', function () {
            return static::first() ?? static::create([
                'platform_name' => 'Tiko',
                'support_email' => 'support@tiko.africa',
                'support_phone' => '+254 700 000 000',
                'platform_fee_percent' => 5,
                'currency' => 'KES',
                'payment_driver' => 'manual',
            ]);
        });
    }

    public function formatPrice(int $amount): string
    {
        return $this->currency.' '.number_format($amount);
    }

    public function platformFee(int $amount): int
    {
        return (int) round($amount * $this->platform_fee_percent / 100);
    }
}
