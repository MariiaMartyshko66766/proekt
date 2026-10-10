<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TicketService
{
    /**
     * Statusy, na które użytkownik może ręcznie zmienić status zgłoszenia.
     */
    public function allowedTransitions(Ticket $ticket, User $user): array
    {
        $isStaff = $user->isAdmin() || $user->isAgent();
        $isOwner = (int) $ticket->user_id === (int) $user->id;

        return match ($ticket->status) {
            Ticket::STATUS_IN_PROGRESS => $isStaff
                ? [Ticket::STATUS_WAITING, Ticket::STATUS_RESOLVED]
                : [],

            Ticket::STATUS_RESOLVED => array_values(array_filter([
                ($isOwner || $user->isAdmin()) ? Ticket::STATUS_CLOSED : null,
                ($isOwner || $isStaff) ? Ticket::STATUS_IN_PROGRESS : null,
            ])),

            Ticket::STATUS_CLOSED => $user->isAdmin() ? [Ticket::STATUS_IN_PROGRESS] : [],

            // "Nowe" zmienia się tylko przez przejęcie lub przypisanie,
            // "Oczekuje na odpowiedź" automatycznie po odpowiedzi autora
            default => [],
        };
    }

    public function changeStatus(Ticket $ticket, User $user, string $to): void
    {
        if (! in_array($to, $this->allowedTransitions($ticket, $user), true)) {
            throw ValidationException::withMessages([
                'status' => 'Ta zmiana statusu jest niedozwolona.',
            ]);
        }

        DB::transaction(function () use ($ticket, $user, $to) {
            $this->applyStatus($ticket, $user, $to);
        });
    }

    /**
     * Technik przejmuje wolne zgłoszenie (status zmienia się na "W realizacji").
     */
    public function take(Ticket $ticket, User $agent): void
    {
        DB::transaction(function () use ($ticket, $agent) {
            // blokada wiersza: dwóch techników nie przejmie tego samego zgłoszenia
            $fresh = Ticket::whereKey($ticket->id)->lockForUpdate()->firstOrFail();

            if ($fresh->agent_id !== null || $fresh->status !== Ticket::STATUS_NEW) {
                throw ValidationException::withMessages([
                    'ticket' => 'To zgłoszenie zostało już przejęte przez innego technika.',
                ]);
            }

            $this->assign($fresh, $agent, $agent);
        });
    }

    /**
     * Przypisanie technika (administrator wybiera dowolnego).
     */
    public function assign(Ticket $ticket, User $actor, User $agent): void
    {
        if (! $agent->isAgent() || ! $agent->is_active) {
            throw ValidationException::withMessages([
                'agent_id' => 'Wybrany użytkownik nie jest aktywnym technikiem.',
            ]);
        }

        if ((int) $ticket->agent_id === (int) $agent->id) {
            return;
        }

        DB::transaction(function () use ($ticket, $actor, $agent) {
            $oldName = $ticket->agent?->name;

            $ticket->agent_id = $agent->id;
            $ticket->save();

            $this->log($ticket, $actor, 'agent', $oldName, $agent->name);

            if ($ticket->status === Ticket::STATUS_NEW) {
                $this->applyStatus($ticket, $actor, Ticket::STATUS_IN_PROGRESS);
            }
        });
    }

    /**
     * Zmiana priorytetu i kategorii (każda zmiana trafia do historii).
     */
    public function updateDetails(Ticket $ticket, User $user, string $priority, int $categoryId): void
    {
        DB::transaction(function () use ($ticket, $user, $priority, $categoryId) {
            if ($ticket->priority !== $priority) {
                $old = $ticket->priority;
                $ticket->priority = $priority;
                $this->log($ticket, $user, 'priority', $old, $priority);
            }

            if ((int) $ticket->category_id !== $categoryId) {
                $oldName = $ticket->category->name;
                $newName = Category::findOrFail($categoryId)->name;
                $ticket->category_id = $categoryId;
                $this->log($ticket, $user, 'category', $oldName, $newName);
            }

            $ticket->save();
        });
    }

    /**
     * Dodanie komentarza. Odpowiedź autora w statusie "Oczekuje na odpowiedź"
     * automatycznie zmienia status na "W realizacji".
     */
    public function addComment(Ticket $ticket, User $user, string $body): TicketComment
    {
        return DB::transaction(function () use ($ticket, $user, $body) {
            $comment = $ticket->comments()->create([
                'user_id' => $user->id,
                'body' => $body,
            ]);

            if ($ticket->status === Ticket::STATUS_WAITING
                && (int) $ticket->user_id === (int) $user->id) {
                $this->applyStatus($ticket, $user, Ticket::STATUS_IN_PROGRESS);
            }

            return $comment;
        });
    }

    private function applyStatus(Ticket $ticket, User $user, string $to): void
    {
        $from = $ticket->status;

        $ticket->status = $to;

        if ($to === Ticket::STATUS_CLOSED) {
            $ticket->closed_at = now();
        } elseif ($from === Ticket::STATUS_CLOSED) {
            $ticket->closed_at = null;
        }

        $ticket->save();

        $this->log($ticket, $user, 'status', $from, $to);
    }

    private function log(Ticket $ticket, User $user, string $field, ?string $old, ?string $new): void
    {
        $ticket->history()->create([
            'user_id' => $user->id,
            'field' => $field,
            'old_value' => $old,
            'new_value' => $new,
        ]);
    }
}