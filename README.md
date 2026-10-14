# HelpDesk – system zgłoszeń

System webowy typu HelpDesk wykonany w ramach pracy dyplomowej. Umożliwia użytkownikom zgłaszanie problemów, a technikom i administratorowi ich obsługę.

## Funkcje

- rejestracja, logowanie i reset hasła,
- trzy role: Użytkownik, Technik, Administrator,
- tworzenie zgłoszeń z załącznikiem (jpg, png, pdf, docx, do 5 MB),
- lista zgłoszeń z filtrami (status, priorytet, kategoria) i wyszukiwaniem,
- przejmowanie i przypisywanie zgłoszeń, zmiana statusu z kontrolą dozwolonych przejść,
- komentarze i historia zmian,
- powiadomienia e-mail (lokalnie zapisywane do pliku `storage/logs/laravel.log`),
- panel główny ze statystykami,
- zarządzanie użytkownikami i kategoriami (administrator).

## Technologie

Laravel 12, PHP 8.2+, MySQL, Blade, Bootstrap 5, Vite, PHPUnit.

## Wymagania

- PHP 8.2 lub nowszy,
- Composer 2,
- Node.js 18 lub nowszy (z npm),
- MySQL 8 lub MariaDB 10.4+,
- Git.

Najprościej zainstalować pakiet Laragon (Windows), który zawiera wszystkie te programy.

## Instalacja

1. Pobierz projekt i wejdź do jego folderu:
```
   git clone <adres-repozytorium> helpdesk
   cd helpdesk
```
2. Zainstaluj zależności PHP:
```
   composer install
```
3. Utwórz plik konfiguracyjny i klucz aplikacji:
```
   copy .env.example .env
   php artisan key:generate
```
   (na Linuksie i macOS zamiast `copy` użyj `cp`).
4. Utwórz pustą bazę danych:
```
   mysql -u root -e "CREATE DATABASE helpdesk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```
   Jeśli Twój MySQL ma inną nazwę użytkownika lub hasło, ustaw je w pliku `.env` (zmienne `DB_USERNAME` i `DB_PASSWORD`).
5. Utwórz tabele i wczytaj dane testowe:
```
   php artisan migrate --seed
```
6. Zainstaluj i zbuduj zasoby front-endu:
```
   npm install
   npm run build
```
7. Uruchom serwer:
```
   php artisan serve
```
   Aplikacja jest dostępna pod adresem http://127.0.0.1:8000.

Alternatywnie, zamiast kroków 5, można zaimportować gotowy zrzut bazy z pliku `docs/helpdesk.sql` (zawiera te same dane testowe).

## Konta testowe

Hasło dla wszystkich kont: `haslo1234`

| Rola | Imię i nazwisko | E-mail |
|---|---|---|
| Administrator | Anna Kowalska | admin@helpdesk.pl |
| Technik | Piotr Nowak | technik1@helpdesk.pl |
| Technik | Katarzyna Wiśniewska | technik2@helpdesk.pl |
| Technik | Tomasz Zieliński | technik3@helpdesk.pl |
| Użytkownik | Marek Lewandowski | uzytkownik1@helpdesk.pl |
| Użytkownik | Joanna Kamińska | uzytkownik2@helpdesk.pl |
| Użytkownik | Michał Wójcik | uzytkownik3@helpdesk.pl |
| Użytkownik | Agnieszka Dąbrowska | uzytkownik4@helpdesk.pl |
| Użytkownik (zablokowany) | Paweł Kozłowski | uzytkownik5@helpdesk.pl |

Konto `uzytkownik5@helpdesk.pl` jest zablokowane i służy do sprawdzenia, że logowanie takiego konta jest niemożliwe.

## Role i uprawnienia

| Funkcja | Użytkownik | Technik | Administrator |
|---|:---:|:---:|:---:|
| Tworzenie zgłoszenia | tak | nie | tak |
| Podgląd zgłoszeń | tylko własne | wszystkie | wszystkie |
| Komentowanie (zgłoszenie niezamknięte) | własne | wszystkie | wszystkie |
| Przejęcie wolnego zgłoszenia | nie | tak | nie |
| Przypisanie technika | nie | nie | tak |
| Zmiana priorytetu i kategorii | nie | tak | tak |
| Zamknięcie zgłoszenia rozwiązanego | własne | nie | tak |
| Ponowne otwarcie zamkniętego zgłoszenia | nie | nie | tak |
| Zarządzanie użytkownikami i kategoriami | nie | nie | tak |

## Statusy zgłoszeń i dozwolone przejścia

| Przejście | Kto może je wykonać |
|---|---|
| Nowe → W realizacji | technik (przejęcie) lub administrator (przypisanie) |
| W realizacji → Oczekuje na odpowiedź | technik, administrator |
| W realizacji → Rozwiązane | technik, administrator |
| Oczekuje na odpowiedź → W realizacji | automatycznie po komentarzu autora zgłoszenia |
| Rozwiązane → Zamknięte | autor zgłoszenia, administrator |
| Rozwiązane → W realizacji | autor zgłoszenia, technik, administrator |
| Zamknięte → W realizacji | administrator |

Pozostałe przejścia są blokowane po stronie serwera.

## Testy

```
php artisan test
```

Testy automatyczne (PHPUnit) używają bazy SQLite w pamięci, dlatego wymagają włączonych rozszerzeń PHP `pdo_sqlite` i `sqlite3`. Nie modyfikują bazy MySQL z danymi testowymi.

## Struktura projektu (najważniejsze pliki)

| Plik | Zadanie |
|---|---|
| `database/migrations/` | struktura bazy danych |
| `database/seeders/` | dane testowe (administrator, technicy, użytkownicy, kategorie, zgłoszenia) |
| `app/Models/` | modele Eloquent i relacje |
| `app/Http/Middleware/RoleMiddleware.php` | ograniczanie dostępu według roli |
| `app/Http/Middleware/EnsureUserIsActive.php` | wylogowanie zablokowanego użytkownika |
| `app/Policies/TicketPolicy.php` | uprawnienia do operacji na zgłoszeniach |
| `app/Services/TicketService.php` | logika statusów, przypisań, komentarzy, historii i powiadomień |
| `app/Http/Controllers/` | kontrolery (zgłoszenia, komentarze, załączniki, panel) |
| `app/Http/Controllers/Admin/` | zarządzanie użytkownikami i kategoriami |
| `app/Notifications/` | powiadomienia e-mail |
| `resources/views/` | widoki Blade (Bootstrap 5) |
| `lang/pl/` | polskie komunikaty systemowe |
| `tests/Feature/` | testy automatyczne |
| `docs/` | diagramy i zrzut bazy danych |