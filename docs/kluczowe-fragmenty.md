# Kluczowe fragmenty kodu

Numery linii dotyczą aktualnej wersji plików. Po zmianie kodu uruchom ponownie: `node docs/fragments.js`.

| Lp. | Fragment | Plik | Linie | Opis |
|---|---|---|---|---|
| 1 | Migracja tickets | `database/migrations/2026_10_09_211505_create_tickets_table.php` | 9–27 | Struktura tabeli zgłoszeń: klucze obce (autor, technik, kategoria), domyślny status i priorytet, indeksy |
| 2 | Model Ticket | `app/Models/Ticket.php` | 9–92 | Stałe statusów i priorytetów, pola masowo przypisywane, relacje z użytkownikami, kategorią, komentarzami, historią i załącznikami |
| 3 | TicketController, tworzenie zgłoszenia | `app/Http/Controllers/TicketController.php` | 65–92 | Zapis zgłoszenia ze statusem "Nowe" i opcjonalnym załącznikiem |
| 4 | TicketController, karta zgłoszenia | `app/Http/Controllers/TicketController.php` | 94–107 | Sprawdzenie uprawnień (Policy) i przekazanie do widoku dozwolonych przejść statusu |
| 5 | TicketController, zmiana statusu | `app/Http/Controllers/TicketController.php` | 137–151 | Walidacja i wywołanie logiki statusów z serwisu |
| 6 | StoreTicketRequest, walidacja | `app/Http/Requests/StoreTicketRequest.php` | 16–25 | Reguły walidacji formularza zgłoszenia, w tym typ i rozmiar załącznika |
| 7 | TicketPolicy | `app/Policies/TicketPolicy.php` | 8–53 | Uprawnienia: kto widzi, tworzy, komentuje, przejmuje, przypisuje i edytuje zgłoszenie |
| 8 | RoleMiddleware | `app/Http/Middleware/RoleMiddleware.php` | 11–20 | Dostęp do tras tylko dla wskazanych ról (kod 403 w przeciwnym razie) |
| 9 | EnsureUserIsActive | `app/Http/Middleware/EnsureUserIsActive.php` | 12–26 | Wylogowanie zablokowanego użytkownika w trakcie sesji |
| 10 | Trasy zalogowanego użytkownika | `routes/web.php` | 16–63 | Wszystkie trasy aplikacji wraz z przypisanymi middleware i rolami |
| 11 | Logika statusów, dozwolone przejścia | `app/Services/TicketService.php` | 18–42 | Tabela dozwolonych przejść statusu zależnie od roli i właściciela zgłoszenia |
| 12 | Logika statusów, zmiana statusu | `app/Services/TicketService.php` | 44–55 | Blokada niedozwolonych przejść i zapis zmiany w transakcji |
| 13 | Logika statusów, przejęcie zgłoszenia | `app/Services/TicketService.php` | 57–74 | Przejęcie z blokadą wiersza: dwóch techników nie przejmie tego samego zgłoszenia |
| 14 | Logika statusów, komentarz autora | `app/Services/TicketService.php` | 134–159 | Automatyczny powrót do "W realizacji" po odpowiedzi autora i powiadomienie |
| 15 | Logika statusów, zapis statusu | `app/Services/TicketService.php` | 161–182 | Zmiana statusu, data zamknięcia, wpis do historii i powiadomienie e-mail |
| 16 | Test niedozwolonego przejścia | `tests/Feature/TicketWorkflowTest.php` | 103–115 | Test automatyczny: z "Nowe" nie można przejść do "Zamknięte" |
