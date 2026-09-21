<?php

namespace App\Services;

use App\Models\Ticket;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;

class QrCodeService
{
    /**
     * The signed value encoded inside a ticket's QR code. Contains only
     * the ticket's random UUID plus an HMAC signature — never customer
     * information — so scanning always re-verifies against the database.
     */
    public function signedPayload(Ticket $ticket): string
    {
        $signature = hash_hmac('sha256', $ticket->uuid, config('app.key'));

        return base64_encode($ticket->uuid . '.' . $signature);
    }

    /**
     * Verify a scanned payload and resolve it back to a ticket. Returns
     * null if the signature is invalid/tampered or the ticket no longer exists.
     */
    public function verify(string $payload): ?Ticket
    {
        $decoded = base64_decode($payload, true);

        if ($decoded === false || ! str_contains($decoded, '.')) {
            return null;
        }

        [$uuid, $signature] = explode('.', $decoded, 2);
        $expected = hash_hmac('sha256', $uuid, config('app.key'));

        if (! hash_equals($expected, $signature)) {
            return null;
        }

        return Ticket::where('uuid', $uuid)->first();
    }

    public function dataUri(Ticket $ticket): string
    {
        $result = (new Builder())->build(
            data: $this->signedPayload($ticket),
            size: 320,
            margin: 12,
            foregroundColor: new Color(17, 17, 17),
            backgroundColor: new Color(255, 255, 255),
        );

        return $result->getDataUri();
    }
}
