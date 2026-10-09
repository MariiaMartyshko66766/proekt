<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(2026); // te same dane przy każdym uruchomieniu

        $categoryIds = Category::pluck('id', 'name');
        $users = User::where('role', User::ROLE_USER)->get()->values();
        $agents = User::where('role', User::ROLE_AGENT)->get()->values();
        $priorities = Ticket::priorities();

        $templates = [
            ['Sprzęt', 'Komputer nie uruchamia się', 'Po naciśnięciu przycisku zasilania komputer nie reaguje, kontrolki nie świecą.'],
            ['Sprzęt', 'Monitor miga i wyłącza się', 'Obraz na monitorze co kilka minut znika, a po chwili wraca.'],
            ['Sprzęt', 'Wymiana myszy i klawiatury', 'Klawiatura ma uszkodzone klawisze, a mysz działa z przerwami. Proszę o wymianę zestawu.'],
            ['Sprzęt', 'Laptop bardzo głośno pracuje', 'Wentylator laptopa działa na maksymalnych obrotach, a obudowa mocno się nagrzewa.'],
            ['Oprogramowanie', 'Brak możliwości instalacji programu', 'System wyświetla komunikat o braku uprawnień przy instalacji wymaganego programu.'],
            ['Oprogramowanie', 'Pakiet Office wymaga aktywacji', 'Przy otwieraniu dokumentów pojawia się informacja o wygaśnięciu licencji.'],
            ['Oprogramowanie', 'Program księgowy zawiesza się', 'Aplikacja przestaje odpowiadać podczas generowania raportów miesięcznych.'],
            ['Oprogramowanie', 'Aktualizacja systemu kończy się błędem', 'Aktualizacja zatrzymuje się na 30 procentach i komputer wraca do poprzedniej wersji.'],
            ['Oprogramowanie', 'Przeglądarka nie otwiera stron firmowych', 'Strony wewnętrzne wyświetlają błąd certyfikatu bezpieczeństwa.'],
            ['Sieć i Internet', 'Brak dostępu do Internetu w pokoju', 'Od rana żaden komputer w pokoju nie ma połączenia z Internetem.'],
            ['Sieć i Internet', 'Bardzo wolne połączenie Wi-Fi', 'Sieć bezprzewodowa działa wolno, a strony ładują się po kilkadziesiąt sekund.'],
            ['Sieć i Internet', 'Brak dostępu do dysku sieciowego', 'Folder wspólny nie otwiera się, system wyświetla komunikat o braku dostępu do ścieżki sieciowej.'],
            ['Sieć i Internet', 'Nie działa połączenie VPN', 'Klient VPN łączy się, ale po chwili połączenie jest zrywane.'],
            ['Sieć i Internet', 'Prośba o dodatkowe gniazdo sieciowe', 'W nowym stanowisku pracy brakuje gniazda sieciowego do podłączenia komputera.'],
            ['Konta i dostęp', 'Reset hasła do konta', 'Zapomniałem hasła do konta domenowego i nie mogę zalogować się do komputera.'],
            ['Konta i dostęp', 'Konto zostało zablokowane', 'Po kilku nieudanych próbach logowania konto zostało zablokowane.'],
            ['Konta i dostęp', 'Prośba o dostęp do systemu kadrowego', 'Do realizacji nowych zadań potrzebuję dostępu do systemu kadrowego w trybie odczytu.'],
            ['Konta i dostęp', 'Nowy pracownik – założenie konta', 'Prosimy o utworzenie konta pocztowego i domenowego dla nowego pracownika działu sprzedaży.'],
            ['Konta i dostęp', 'Brak dostępu do skrzynki wspólnej', 'Skrzynka e-mail działu nie wyświetla się w programie pocztowym.'],
            ['Drukarki', 'Drukarka nie drukuje', 'Dokumenty trafiają do kolejki wydruku, ale drukarka ich nie drukuje.'],
            ['Drukarki', 'Zacięcie papieru w drukarce', 'Drukarka sygnalizuje zacięcie papieru, choć w podajniku nic nie widać.'],
            ['Drukarki', 'Wydruki są blade i z paskami', 'Na kartkach pojawiają się poziome pasy, a tekst jest słabo widoczny.'],
            ['Drukarki', 'Skaner nie wysyła plików na e-mail', 'Urządzenie wielofunkcyjne skanuje dokument, ale wiadomość z plikiem nie dociera.'],
            ['Inne', 'Prośba o dodatkowy monitor', 'Do pracy z arkuszami potrzebuję drugiego monitora przy stanowisku.'],
            ['Inne', 'Pytanie o zasady korzystania z poczty', 'Proszę o informację, jaki jest maksymalny rozmiar załącznika w wiadomościach e-mail.'],
        ];

        $agentReplies = [
            'Dzień dobry, przyjąłem zgłoszenie i zajmę się nim w pierwszej kolejności.',
            'Proszę o dodatkowe informacje: od kiedy występuje problem i czy pojawia się komunikat o błędzie?',
            'Sprawdziłem ustawienia i wykonałem restart usługi. Proszę o test i informację zwrotną.',
            'Problem został zdiagnozowany, naprawa wymaga około godziny pracy.',
            'Wymieniłem uszkodzony element. Proszę potwierdzić, że wszystko działa poprawnie.',
        ];

        $userReplies = [
            'Dziękuję za szybką reakcję. Problem występuje od wczoraj rano.',
            'Komunikat o błędzie pojawia się za każdym razem po uruchomieniu programu.',
            'Próbowałem restartu, niestety bez rezultatu.',
            'Sprawdziłem – wszystko działa, dziękuję za pomoc!',
            'Problem wrócił, proszę o ponowne sprawdzenie.',
        ];

        // 50 zgłoszeń w różnych statusach
        $statusList = array_merge(
            array_fill(0, 12, Ticket::STATUS_NEW),
            array_fill(0, 12, Ticket::STATUS_IN_PROGRESS),
            array_fill(0, 8, Ticket::STATUS_WAITING),
            array_fill(0, 8, Ticket::STATUS_RESOLVED),
            array_fill(0, 10, Ticket::STATUS_CLOSED),
        );
        shuffle($statusList);

        // kolejne statusy, przez które przeszło zgłoszenie
        $chains = [
            Ticket::STATUS_NEW => [],
            Ticket::STATUS_IN_PROGRESS => [Ticket::STATUS_IN_PROGRESS],
            Ticket::STATUS_WAITING => [Ticket::STATUS_IN_PROGRESS, Ticket::STATUS_WAITING],
            Ticket::STATUS_RESOLVED => [Ticket::STATUS_IN_PROGRESS, Ticket::STATUS_RESOLVED],
            Ticket::STATUS_CLOSED => [Ticket::STATUS_IN_PROGRESS, Ticket::STATUS_RESOLVED, Ticket::STATUS_CLOSED],
        ];

        foreach ($statusList as $i => $status) {
            [$categoryName, $title, $description] = $templates[$i % count($templates)];

            $author = $users[mt_rand(0, $users->count() - 1)];
            $agent = $status === Ticket::STATUS_NEW
                ? null
                : $agents[mt_rand(0, $agents->count() - 1)];

            $created = Carbon::now()->subDays(mt_rand(3, 60))->subHours(mt_rand(0, 23));

            $ticket = new Ticket([
                'user_id' => $author->id,
                'agent_id' => $agent?->id,
                'category_id' => $categoryIds[$categoryName],
                'title' => $title . ' (sala ' . mt_rand(1, 40) . ')',
                'description' => $description,
                'priority' => $priorities[mt_rand(0, count($priorities) - 1)],
                'status' => $status,
            ]);
            $ticket->created_at = $created;
            $ticket->updated_at = $created;
            $ticket->save();

            // kolejne zdarzenia mają rosnące daty
            $cursor = $created->copy();
            $step = function () use (&$cursor) {
                $cursor = $cursor->copy()->addHours(mt_rand(1, 20));
                if ($cursor->gt(Carbon::now())) {
                    $cursor = Carbon::now();
                }
                return $cursor->copy();
            };

            // przejęcie zgłoszenia przez technika
            if ($agent) {
                $this->log($ticket, $agent->id, 'agent', null, $agent->name, $step());
            }

            // zmiana priorytetu w części zgłoszeń
            if ($agent && $ticket->priority !== Ticket::PRIORITY_MEDIUM && mt_rand(1, 4) === 1) {
                $this->log($ticket, $agent->id, 'priority', Ticket::PRIORITY_MEDIUM, $ticket->priority, $step());
            }

            // komentarze
            $commentCount = $status === Ticket::STATUS_NEW ? mt_rand(0, 1) : mt_rand(1, 4);
            for ($c = 0; $c < $commentCount; $c++) {
                $byAgent = $agent && $c % 2 === 0;
                $replies = $byAgent ? $agentReplies : $userReplies;
                $when = $step();

                $comment = new TicketComment([
                    'ticket_id' => $ticket->id,
                    'user_id' => $byAgent ? $agent->id : $author->id,
                    'body' => $replies[mt_rand(0, count($replies) - 1)],
                ]);
                $comment->created_at = $when;
                $comment->updated_at = $when;
                $comment->save();
            }

            // zmiany statusu
            $previous = Ticket::STATUS_NEW;
            $closedAt = null;
            foreach ($chains[$status] as $next) {
                $actor = $next === Ticket::STATUS_CLOSED ? $author : $agent;
                $when = $step();
                $this->log($ticket, $actor->id, 'status', $previous, $next, $when);

                if ($next === Ticket::STATUS_CLOSED) {
                    $closedAt = $when;
                }
                $previous = $next;
            }

            Ticket::where('id', $ticket->id)->update([
                'updated_at' => $cursor,
                'closed_at' => $closedAt,
            ]);
        }
    }

    private function log(Ticket $ticket, int $userId, string $field, ?string $old, ?string $new, Carbon $when): void
    {
        $entry = new TicketHistory([
            'ticket_id' => $ticket->id,
            'user_id' => $userId,
            'field' => $field,
            'old_value' => $old,
            'new_value' => $new,
        ]);
        $entry->created_at = $when;
        $entry->updated_at = $when;
        $entry->save();
    }
}