<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAssigned extends Notification
{
    public function __construct(public Ticket $ticket)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Zgłoszenie #' . $this->ticket->id . ': przypisano do Ciebie')
            ->greeting('Dzień dobry, ' . $notifiable->name . '!')
            ->line('Do Ciebie zostało przypisane zgłoszenie „' . $this->ticket->title . '”.')
            ->line('Priorytet: ' . $this->ticket->priority)
            ->action('Otwórz zgłoszenie', route('tickets.show', $this->ticket))
            ->salutation('Pozdrawiamy, zespół HelpDesk');
    }
}