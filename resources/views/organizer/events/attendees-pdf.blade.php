<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28pt 26pt; }
        body { margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif; color: #171717; font-size: 9pt; }
        .brand { font-size: 16pt; font-weight: bold; color: #111111; }
        .brand span { color: #ff6a00; }
        .header { display: table; width: 100%; margin-bottom: 14pt; }
        .header-left { display: table-cell; vertical-align: top; }
        .header-right { display: table-cell; vertical-align: top; text-align: right; }
        .event-name { font-size: 13pt; font-weight: bold; margin: 4pt 0 2pt; }
        .event-meta { font-size: 8.5pt; color: #737373; }
        .generated { font-size: 8pt; color: #a3a3a3; }
        .count-badge { display: inline-block; margin-top: 4pt; padding: 3pt 8pt; background: #fff3ea; color: #e05a00; font-size: 8.5pt; font-weight: bold; border-radius: 10pt; }

        table.attendees { width: 100%; border-collapse: collapse; margin-top: 8pt; }
        table.attendees th {
            text-align: left; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.5pt;
            color: #737373; border-bottom: 1pt solid #e5e5e5; padding: 6pt 5pt; background: #fafafa;
        }
        table.attendees td { padding: 6pt 5pt; border-bottom: 0.5pt solid #f0f0f0; vertical-align: middle; }
        table.attendees tr:nth-child(even) td { background: #fcfcfc; }
        .ticket-no { font-weight: bold; color: #171717; }
        .muted { color: #a3a3a3; }
        .checkbox {
            display: block; width: 11pt; height: 11pt; border: 1pt solid #a3a3a3; border-radius: 2pt;
            text-align: center; font-size: 8pt; line-height: 10pt;
        }
        .checkbox.checked { background: #f0fdf4; border-color: #16a34a; color: #16a34a; font-weight: bold; }
        .col-num { width: 22pt; color: #a3a3a3; }
        .col-check { width: 26pt; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="brand">Tik<span>o</span></div>
            <div class="event-name">{{ $event->name }}</div>
            <div class="event-meta">{{ $event->event_date->format('D, d M Y') }} · {{ $event->venue }}, {{ $event->location }}</div>
        </div>
        <div class="header-right">
            <div class="generated">Generated {{ now()->format('d M Y, g:i A') }}</div>
            <div class="count-badge">{{ $tickets->count() }} {{ \Illuminate\Support\Str::plural('ticket', $tickets->count()) }}</div>
        </div>
    </div>

    <table class="attendees">
        <thead>
            <tr>
                <th class="col-num">#</th>
                <th>Ticket No.</th>
                <th>Guest</th>
                <th>Ticket Type</th>
                <th>Phone</th>
                <th class="col-check">In</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $index => $ticket)
                <tr>
                    <td class="col-num">{{ $index + 1 }}</td>
                    <td class="ticket-no">{{ $ticket->ticket_number }}</td>
                    <td>{{ $ticket->orderItem->order->customer_name }}</td>
                    <td>{{ $ticket->orderItem->ticketType->name }}</td>
                    <td class="muted">{{ $ticket->orderItem->order->customer_whatsapp }}</td>
                    <td class="col-check">
                        <span class="checkbox {{ $ticket->checked_in ? 'checked' : '' }}">{{ $ticket->checked_in ? 'X' : '' }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="muted">No paid tickets for this event yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
