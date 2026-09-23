<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrganizerRegistered extends Notification
{
    public function __construct(public bool $requiresApproval) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Welcome to MyTicket')
            ->greeting("Hi {$notifiable->name},")
            ->line("Thanks for registering **{$notifiable->business_name}** as an organizer on MyTicket.");

        if ($this->requiresApproval) {
            return $message
                ->line('Your account is currently pending review. We will email you as soon as it is approved and you can start creating events.');
        }

        return $message
            ->line('Your account is ready to go — you can log in now and start creating events.')
            ->action('Log In', route('organizer.login'));
    }
}
