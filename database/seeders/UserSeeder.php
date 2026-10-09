<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = 'haslo1234';

        // 1 administrator
        User::create([
            'name' => 'Anna Kowalska',
            'email' => 'admin@helpdesk.pl',
            'password' => $password,
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
        ]);

        // 3 technicy
        $agents = [
            ['Piotr Nowak', 'technik1@helpdesk.pl'],
            ['Katarzyna Wiśniewska', 'technik2@helpdesk.pl'],
            ['Tomasz Zieliński', 'technik3@helpdesk.pl'],
        ];

        foreach ($agents as [$name, $email]) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => User::ROLE_AGENT,
                'is_active' => true,
            ]);
        }

        // 5 użytkowników (ostatni jest zablokowany, do testu logowania)
        $users = [
            ['Marek Lewandowski', 'uzytkownik1@helpdesk.pl', true],
            ['Joanna Kamińska', 'uzytkownik2@helpdesk.pl', true],
            ['Michał Wójcik', 'uzytkownik3@helpdesk.pl', true],
            ['Agnieszka Dąbrowska', 'uzytkownik4@helpdesk.pl', true],
            ['Paweł Kozłowski', 'uzytkownik5@helpdesk.pl', false],
        ];

        foreach ($users as [$name, $email, $active]) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => User::ROLE_USER,
                'is_active' => $active,
            ]);
        }
    }
}