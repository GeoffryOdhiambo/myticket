<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * M-Pesa STK Push driver (Safaricom Daraja API). Requires MPESA_* env
 * credentials to be configured; until then, selecting this driver in
 * Settings will fail loudly on checkout rather than silently no-op.
 */
class MpesaPaymentService extends AbstractPaymentService
{
    protected function baseUrl(): string
    {
        return config('services.mpesa.env') === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    protected function accessToken(): string
    {
        $response = Http::withBasicAuth(
            config('services.mpesa.consumer_key'),
            config('services.mpesa.consumer_secret')
        )->get("{$this->baseUrl()}/oauth/v1/generate", ['grant_type' => 'client_credentials'])->throw();

        return $response->json('access_token');
    }

    public function initiate(Order $order): void
    {
        $shortcode = config('services.mpesa.shortcode');
        $timestamp = now()->format('YmdHis');
        $password = base64_encode($shortcode . config('services.mpesa.passkey') . $timestamp);
        $phone = $this->normalizePhone($order->customer_whatsapp);

        $response = Http::withToken($this->accessToken())
            ->post("{$this->baseUrl()}/mpesa/stkpush/v1/processrequest", [
                'BusinessShortCode' => $shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $order->total,
                'PartyA' => $phone,
                'PartyB' => $shortcode,
                'PhoneNumber' => $phone,
                'CallBackURL' => config('services.mpesa.callback_url'),
                'AccountReference' => $order->order_number,
                'TransactionDesc' => "Tiko ticket - {$order->event->name}",
            ])
            ->throw()
            ->json();

        $order->payment()->updateOrCreate([], [
            'provider' => 'mpesa',
            'amount' => $order->total,
            'status' => 'pending',
            'checkout_request_id' => $response['CheckoutRequestID'] ?? null,
            'raw_response' => $response,
        ]);
    }

    public function handleCallback(array $payload): void
    {
        $callback = data_get($payload, 'Body.stkCallback');

        if (! $callback) {
            Log::warning('[Mpesa] Unrecognized callback payload', $payload);

            return;
        }

        $payment = Payment::where('checkout_request_id', $callback['CheckoutRequestID'] ?? null)->first();

        if (! $payment) {
            Log::warning('[Mpesa] Callback for unknown CheckoutRequestID', $callback);

            return;
        }

        if ((int) ($callback['ResultCode'] ?? 1) !== 0) {
            $this->failPayment($payment, $callback);

            return;
        }

        $items = collect(data_get($callback, 'CallbackMetadata.Item', []));
        $receipt = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;

        $this->confirmPayment($payment, $receipt, $callback);
    }

    protected function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (str_starts_with($digits, '0')) {
            $digits = '254' . substr($digits, 1);
        }

        return $digits;
    }
}
