<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Organizer;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketCheckInTest extends TestCase
{
    use RefreshDatabase;

    private function createTicketForEvent(): array
    {
        $ticketType = TicketType::factory()->create();
        $order = Order::factory()->create(['event_id' => $ticketType->event_id]);
        $orderItem = OrderItem::factory()->create(['ticket_type_id' => $ticketType->id, 'order_id' => $order->id]);
        $ticket = Ticket::factory()->create(['order_item_id' => $orderItem->id]);

        return [$ticket, $ticketType->event->organizer];
    }

    public function test_a_ticket_cannot_be_checked_in_twice(): void
    {
        [$ticket, $organizer] = $this->createTicketForEvent();

        $this->actingAs($organizer, 'organizer');

        $first = $this->postJson(route('organizer.scanner.checkin', $ticket));
        $first->assertJson(['status' => 'checked_in']);

        $second = $this->postJson(route('organizer.scanner.checkin', $ticket));
        $second->assertJson(['status' => 'used']);

        $ticket->refresh();
        $this->assertTrue($ticket->checked_in);
        // The second attempt must not have overwritten who/when checked in.
        $this->assertSame($first->json('ticket.checked_in_at'), $second->json('ticket.checked_in_at'));
    }

    public function test_an_organizer_cannot_check_in_another_organizers_ticket(): void
    {
        [$ticket] = $this->createTicketForEvent();

        $otherOrganizer = Organizer::factory()->create();
        $this->actingAs($otherOrganizer, 'organizer');

        $response = $this->postJson(route('organizer.scanner.checkin', $ticket));

        $response->assertStatus(404);
        $this->assertFalse($ticket->fresh()->checked_in);
    }
}
