<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['name' => 'US Dollar', 'symbol' => '$'],
            ['name' => 'Euro', 'symbol' => '€'],
            ['name' => 'British Pound', 'symbol' => '£'],
            ['name' => 'Japanese Yen', 'symbol' => '¥'],
            ['name' => 'Canadian Dollar', 'symbol' => 'C$'],
            ['name' => 'Australian Dollar', 'symbol' => 'A$'],
            ['name' => 'Swiss Franc', 'symbol' => 'Fr'],
            ['name' => 'Chinese Yuan', 'symbol' => '¥'],
            ['name' => 'Indian Rupee', 'symbol' => '₹'],
            ['name' => 'Mexican Peso', 'symbol' => '$'],
            ['name' => 'Brazilian Real', 'symbol' => 'R$'],
            ['name' => 'South African Rand', 'symbol' => 'R'],
            ['name' => 'Russian Ruble', 'symbol' => '₽'],
            ['name' => 'Korean Won', 'symbol' => '₩'],
            ['name' => 'Turkish Lira', 'symbol' => '₺'],
            ['name' => 'Saudi Riyal', 'symbol' => '﷼'],
            ['name' => 'UAE Dirham', 'symbol' => 'د.إ'],
            ['name' => 'Singapore Dollar', 'symbol' => 'S$'],
            ['name' => 'Hong Kong Dollar', 'symbol' => 'HK$'],
            ['name' => 'Norwegian Krone', 'symbol' => 'kr'],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }

        $this->command->info('Currencies seeded successfully!');
    }
}
