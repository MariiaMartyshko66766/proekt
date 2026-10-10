<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class TicketCommentAdded extends Notification
{
    public function __construct(
        public Ticket $ticket,
        public TicketComment $comment,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Zgłoszenie #' . $this->ticket->id . ': nowy komentarz')
            ->greeting('Dzień dobry, ' . $notifiable->name . '!')
            ->line('W Twoim zgłoszeniu „' . $this->ticket->title . '” pojawił się nowy komentarz.')
            ->line('Autor: ' . $this->comment->user->name)
            ->line(Str::limit($this->comment->body, 200))
            ->action('Otwórz zgłoszenie', route('tickets.show', $this->ticket))
            ->salutation('Pozdrawiamy, zespół HelpDesk');
    }
}