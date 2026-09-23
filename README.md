# Tiko

Tiko is a lightweight online event ticketing platform: customers discover events and buy tickets
without creating an account, organizers publish events and manage sales from their own dashboard,
and a Super Admin oversees the whole platform. Tickets are delivered as a QR-coded PDF by email
(and WhatsApp, via a swappable notification driver) and checked in with a mobile-optimized scanner.

Built with Laravel 12, Blade, Tailwind CSS, and Alpine.js.

## Requirements

- PHP 8.2+
- MySQL
- Node.js / npm
- The PHP `gd` extension (for QR code generation)

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Create a `tiko` MySQL database (or update `DB_*` in `.env` to point at your own), then:

```bash
php artisan migrate --seed
php artisan storage:link
```

Run the dev server and asset watcher together:

```bash
php artisan serve
npm run dev
```

Visit `http://localhost:8000`.

## Seeded accounts

| Role | Email | Password |
| --- | --- | --- |
| Super Admin | `admin@tiko.africa` | `password` |
| Organizer | `organizer1@tiko.africa` | `password` |
| Organizer | `organizer2@tiko.africa` | `password` |
| Organizer | `organizer3@tiko.africa` | `password` |

## Payments

The active payment driver is set in **Admin → Settings** (`payment_driver`, default `manual`).

- **Manual** — the default for local development. Checkout creates a pending payment and the
  order's status page exposes a "Simulate Payment" action that confirms it, exercising the full
  checkout → payment → ticket-generation → notification pipeline without needing real credentials.
- **M-Pesa (STK Push)** — implemented against Safaricom's Daraja API (`app/Services/Payments/MpesaPaymentService.php`).
  To go live, set `MPESA_ENV`, `MPESA_CONSUMER_KEY`, `MPESA_CONSUMER_SECRET`, `MPESA_SHORTCODE`,
  `MPESA_PASSKEY`, and `MPESA_CALLBACK_URL` in `.env`, then switch the driver to `mpesa` in Settings.

## Notifications

- **Email** ships fully working (`MAIL_MAILER=log` by default, so messages land in `storage/logs/laravel.log`
  during development). The `TicketPurchased` notification (`app/Notifications/TicketPurchased.php`) sends the
  confirmation with each ticket's PDF attached — no code changes are needed to switch providers, only `.env`.
  - **Resend** — the `resend/resend-php` SDK is already installed and Laravel's `resend` mailer is
    preconfigured. The plan is to send from `myticket.top`. To go live: in the
    [Resend dashboard](https://resend.com/domains), add `myticket.top` as a domain and add the SPF/DKIM
    DNS records it gives you at your domain registrar (verification can take a few minutes to a few
    hours to propagate). Once it shows "Verified", create an API key and set in `.env`:
    ```
    MAIL_MAILER=resend
    RESEND_API_KEY=re_your_key_here
    MAIL_FROM_ADDRESS=tickets@myticket.top
    ```
  - Any other provider Laravel supports out of the box (SMTP, SES, Postmark, Mailgun) works the same way —
    just set the matching `MAIL_*` variables.
- **WhatsApp** uses a `WhatsAppServiceInterface` with a `LogWhatsAppService` default driver (logs what
  would be sent). Swap the binding in `AppServiceProvider` for a real provider (Twilio, Meta Cloud API, etc.)
  when one is chosen.

## Testing

```bash
php artisan test
```

## Project structure notes

- Three portals: the public site (`routes/web.php`), the organizer dashboard (`routes/organizer.php`,
  guard `organizer`), and the Super Admin dashboard (`routes/admin.php`, guard `web`).
- Customers never create an account — checkout only collects name, WhatsApp number, and email.
- Every ticket's QR code encodes an HMAC-signed random UUID (no customer data, no predictable IDs);
  scanning always re-verifies against the database in `App\Services\QrCodeService`.
