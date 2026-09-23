<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrganizerApproved extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your MyTicket organizer account has been approved')
            ->greeting("Hi {$notifiable->name},")
            ->line("Good news — **{$notifiable->business_name}** has been approved on MyTicket.")
            ->line('You can now log in and start creating events.')
            ->action('Log In', route('organizer.login'));
    }
}
