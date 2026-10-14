// Generuje plik docs/kluczowe-fragmenty.md z numerami linii najważniejszych fragmentów kodu.
// Uruchomienie (z głównego folderu projektu):  node docs/fragments.js

const fs = require('fs');
const path = require('path');

const root = process.cwd();

function findMigration(suffix) {
    const dir = path.join(root, 'database', 'migrations');
    const name = fs.readdirSync(dir).find((f) => f.endsWith(suffix));
    return name ? path.join('database', 'migrations', name) : null;
}

const ticketsMigration = findMigration('_create_tickets_table.php');

const fragments = [
    ['Migracja tickets', ticketsMigration, 'public function up()', 'Struktura tabeli zgłoszeń: klucze obce (autor, technik, kategoria), domyślny status i priorytet, indeksy'],
    ['Model Ticket', 'app/Models/Ticket.php', 'class Ticket extends Model', 'Stałe statusów i priorytetów, pola masowo przypisywane, relacje z użytkownikami, kategorią, komentarzami, historią i załącznikami'],
    ['TicketController, tworzenie zgłoszenia', 'app/Http/Controllers/TicketController.php', 'public function store(', 'Zapis zgłoszenia ze statusem "Nowe" i opcjonalnym załącznikiem'],
    ['TicketController, karta zgłoszenia', 'app/Http/Controllers/TicketController.php', 'public function show(', 'Sprawdzenie uprawnień (Policy) i przekazanie do widoku dozwolonych przejść statusu'],
    ['TicketController, zmiana statusu', 'app/Http/Controllers/TicketController.php', 'public function updateStatus(', 'Walidacja i wywołanie logiki statusów z serwisu'],
    ['StoreTicketRequest, walidacja', 'app/Http/Requests/StoreTicketRequest.php', 'public function rules()', 'Reguły walidacji formularza zgłoszenia, w tym typ i rozmiar załącznika'],
    ['TicketPolicy', 'app/Policies/TicketPolicy.php', 'class TicketPolicy', 'Uprawnienia: kto widzi, tworzy, komentuje, przejmuje, przypisuje i edytuje zgłoszenie'],
    ['RoleMiddleware', 'app/Http/Middleware/RoleMiddleware.php', 'public function handle(', 'Dostęp do tras tylko dla wskazanych ról (kod 403 w przeciwnym razie)'],
    ['EnsureUserIsActive', 'app/Http/Middleware/EnsureUserIsActive.php', 'public function handle(', 'Wylogowanie zablokowanego użytkownika w trakcie sesji'],
    ['Trasy zalogowanego użytkownika', 'routes/web.php', "Route::middleware(['auth', 'active'])->group(", 'Wszystkie trasy aplikacji wraz z przypisanymi middleware i rolami'],
    ['Logika statusów, dozwolone przejścia', 'app/Services/TicketService.php', 'public function allowedTransitions(', 'Tabela dozwolonych przejść statusu zależnie od roli i właściciela zgłoszenia'],
    ['Logika statusów, zmiana statusu', 'app/Services/TicketService.php', 'public function changeStatus(', 'Blokada niedozwolonych przejść i zapis zmiany w transakcji'],
    ['Logika statusów, przejęcie zgłoszenia', 'app/Services/TicketService.php', 'public function take(', 'Przejęcie z blokadą wiersza: dwóch techników nie przejmie tego samego zgłoszenia'],
    ['Logika statusów, komentarz autora', 'app/Services/TicketService.php', 'public function addComment(', 'Automatyczny powrót do "W realizacji" po odpowiedzi autora i powiadomienie'],
    ['Logika statusów, zapis statusu', 'app/Services/TicketService.php', 'private function applyStatus(', 'Zmiana statusu, data zamknięcia, wpis do historii i powiadomienie e-mail'],
    ['Test niedozwolonego przejścia', 'tests/Feature/TicketWorkflowTest.php', 'public function test_invalid_status_transition_is_blocked(', 'Test automatyczny: z "Nowe" nie można przejść do "Zamknięte"'],
];

function stripNoise(line) {
    return line
        .replace(/'(?:[^'\\]|\\.)*'/g, "''")
        .replace(/"(?:[^"\\]|\\.)*"/g, '""')
        .replace(/\/\/.*$/, '');
}

function findRange(lines, marker) {
    const startIdx = lines.findIndex((l) => l.includes(marker));
    if (startIdx === -1) return null;

    let depth = 0;
    let opened = false;
    let endIdx = -1;

    for (let i = startIdx; i < lines.length; i++) {
        const clean = stripNoise(lines[i]);
        for (const ch of clean) {
            if (ch === '{') { depth++; opened = true; }
            if (ch === '}') { depth--; }
        }
        if (opened && depth === 0) { endIdx = i; break; }
    }
    if (endIdx === -1) return null;

    // dołącz komentarz bezpośrednio nad fragmentem
    let from = startIdx;
    while (from > 0) {
        const prev = lines[from - 1].trim();
        if (prev.startsWith('/**') || prev.startsWith('*') || prev.startsWith('//')) from--;
        else break;
    }
    return [from + 1, endIdx + 1];
}

const rows = [];
let problems = 0;

fragments.forEach(([title, file, marker, description], i) => {
    let range = null;
    let note = '';

    if (!file || !fs.existsSync(path.join(root, file))) {
        note = 'BRAK PLIKU';
    } else {
        const lines = fs.readFileSync(path.join(root, file), 'utf8').split(/\r?\n/);
        range = findRange(lines, marker);
        if (!range) note = 'NIE ZNALEZIONO FRAGMENTU';
    }

    if (note) problems++;
    const fileLabel = (file || '?').replace(/\\/g, '/');
    const lineLabel = range ? `${range[0]}–${range[1]}` : note;
    rows.push(`| ${i + 1} | ${title} | \`${fileLabel}\` | ${lineLabel} | ${description} |`);
    console.log(`${String(i + 1).padStart(2)}. ${title}: ${fileLabel} ${lineLabel}`);
});

const out = [
    '# Kluczowe fragmenty kodu',
    '',
    'Numery linii dotyczą aktualnej wersji plików. Po zmianie kodu uruchom ponownie: `node docs/fragments.js`.',
    '',
    '| Lp. | Fragment | Plik | Linie | Opis |',
    '|---|---|---|---|---|',
    ...rows,
    '',
].join('\n');

fs.writeFileSync(path.join(root, 'docs', 'kluczowe-fragmenty.md'), out, 'utf8');

console.log('');
console.log(problems === 0
    ? 'Gotowe: zapisano docs/kluczowe-fragmenty.md'
    : `Zapisano plik, ale w ${problems} pozycjach brakuje danych (patrz wyżej).`);