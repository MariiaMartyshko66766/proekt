<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Sprzęt', 'Problemy z komputerami, monitorami i innym sprzętem biurowym.'],
            ['Oprogramowanie', 'Instalacja, aktywacja i błędy w działaniu programów.'],
            ['Sieć i Internet', 'Połączenie z Internetem, Wi-Fi, VPN i zasoby sieciowe.'],
            ['Konta i dostęp', 'Hasła, blokady kont oraz uprawnienia do systemów.'],
            ['Drukarki', 'Drukarki, skanery i urządzenia wielofunkcyjne.'],
            ['Inne', 'Pozostałe pytania i prośby.'],
        ];

        foreach ($categories as [$name, $description]) {
            Category::create([
                'name' => $name,
                'description' => $description,
            ]);
        }
    }
}