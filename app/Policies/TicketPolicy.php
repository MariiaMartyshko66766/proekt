<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    // użytkownik widzi tylko swoje zgłoszenia, technik i administrator wszystkie
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin() || $user->isAgent() || $ticket->user_id === $user->id;
    }

    // technik nie tworzy zgłoszeń
    public function create(User $user): bool
    {
        return in_array($user->role, [User::ROLE_USER, User::ROLE_ADMIN], true);
    }

    // do zamkniętego zgłoszenia nie można dodawać komentarzy
    public function comment(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket) && $ticket->status !== Ticket::STATUS_CLOSED;
    }

    // technik przejmuje wolne zgłoszenie
    public function take(User $user, Ticket $ticket): bool
    {
        return $user->isAgent()
            && $ticket->agent_id === null
            && $ticket->status === Ticket::STATUS_NEW;
    }

    // administrator przypisuje dowolnego technika
    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin() && $ticket->status !== Ticket::STATUS_CLOSED;
    }

    // zmiana priorytetu i kategorii
    public function updateDetails(User $user, Ticket $ticket): bool
    {
        return ($user->isAdmin() || $user->isAgent())
            && $ticket->status !== Ticket::STATUS_CLOSED;
    }
}