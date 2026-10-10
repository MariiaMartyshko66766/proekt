<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketStatusChanged extends Notification
{
    public function __construct(
        public Ticket $ticket,
        public string $oldStatus,
        public string $newStatus,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Zgłoszenie #' . $this->ticket->id . ': zmiana statusu')
            ->greeting('Dzień dobry, ' . $notifiable->name . '!')
            ->line('Status Twojego zgłoszenia „' . $this->ticket->title . '” został zmieniony.')
            ->line('Poprzedni status: ' . $this->oldStatus)
            ->line('Nowy status: ' . $this->newStatus)
            ->action('Otwórz zgłoszenie', route('tickets.show', $this->ticket))
            ->salutation('Pozdrawiamy, zespół HelpDesk');
    }
}