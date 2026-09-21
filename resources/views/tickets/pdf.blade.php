@php
    $order = $ticket->orderItem->order;
    $event = $order->event;
    $ticketType = $ticket->orderItem->ticketType;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif; color: #171717; }
        .ticket { width: 566.93pt; height: 283.46pt; display: table; table-layout: fixed; }
        .main { display: table-cell; width: 380pt; vertical-align: top; padding: 22pt 20pt; }
        .stub { display: table-cell; width: 186.93pt; vertical-align: top; background: #111111; color: #ffffff; padding: 22pt 18pt; text-align: center; }
        .brand { font-size: 16pt; font-weight: bold; color: #111111; }
        .brand span { color: #ff6a00; }
        .label { font-size: 7pt; text-transform: uppercase; letter-spacing: 1pt; color: #9a9a9a; margin: 0 0 2pt; }
        .value { font-size: 10pt; font-weight: bold; color: #171717; margin: 0 0 10pt; }
        .event-name { font-size: 17pt; font-weight: bold; margin: 10pt 0 4pt; color: #171717; }
        .ticket-type { font-size: 8.5pt; text-transform: uppercase; letter-spacing: 1pt; color: #ff6a00; font-weight: bold; margin: 0; }
        .grid { display: table; width: 100%; margin-top: 8pt; }
        .grid-row { display: table-row; }
        .grid-cell { display: table-cell; width: 33%; padding-top: 6pt; }
        .stub .brand-dark { font-size: 13pt; font-weight: bold; color: #ffffff; }
        .stub .brand-dark span { color: #ff6a00; }
        .stub .tagline { font-size: 6.5pt; text-transform: uppercase; letter-spacing: 1.5pt; color: #9a9a9a; margin: 2pt 0 14pt; }
        .stub img { width: 108pt; height: 108pt; background: #fff; padding: 6pt; }
        .stub .ref { font-size: 8pt; margin-top: 10pt; color: #d4d4d4; }
        .divider { border-top: 1pt dashed #d4d4d4; margin: 10pt 0; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="main">
            <div class="brand">Tik<span>o</span></div>
            <p class="ticket-type">{{ $ticketType->name }}</p>
            <div class="event-name">{{ $event->name }}</div>

            <div class="divider"></div>

            <div class="grid">
                <div class="grid-row">
                    <div class="grid-cell">
                        <p class="label">Date</p>
                        <p class="value">{{ $event->event_date->format('d M Y') }}</p>
                    </div>
                    <div class="grid-cell">
                        <p class="label">Time</p>
                        <p class="value">{{ \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') }}</p>
                    </div>
                    <div class="grid-cell">
                        <p class="label">Venue</p>
                        <p class="value">{{ $event->venue }}</p>
                    </div>
                </div>
                <div class="grid-row">
                    <div class="grid-cell">
                        <p class="label">Guest</p>
                        <p class="value">{{ $order->customer_name }}</p>
                    </div>
                    <div class="grid-cell" colspan="2">
                        <p class="label">Ticket Number</p>
                        <p class="value">{{ $ticket->ticket_number }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="stub">
            <div class="brand-dark">Tik<span>o</span></div>
            <p class="tagline">Admit One</p>
            <img src="{{ $qrDataUri }}" alt="QR">
            <p class="ref">{{ $ticket->ticket_number }}</p>
        </div>
    </div>
</body>
</html>
