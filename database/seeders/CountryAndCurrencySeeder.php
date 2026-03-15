<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Currency;

class CountryAndCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create countries first
        $countries = [
            ['name' => 'United States', 'flag' => '🇺🇸'],
            ['name' => 'Germany', 'flag' => '🇩🇪'],
            ['name' => 'United Kingdom', 'flag' => '🇬🇧'],
            ['name' => 'Japan', 'flag' => '🇯🇵'],
            ['name' => 'Canada', 'flag' => '🇨🇦'],
            ['name' => 'Australia', 'flag' => '🇦🇺'],
            ['name' => 'Switzerland', 'flag' => '🇨🇭'],
            ['name' => 'China', 'flag' => '🇨🇳'],
            ['name' => 'India', 'flag' => '🇮🇳'],
            ['name' => 'Mexico', 'flag' => '🇲🇽'],
            ['name' => 'Brazil', 'flag' => '🇧🇷'],
            ['name' => 'South Africa', 'flag' => '🇿🇦'],
            ['name' => 'Russia', 'flag' => '🇷🇺'],
            ['name' => 'South Korea', 'flag' => '🇰🇷'],
            ['name' => 'Turkey', 'flag' => '🇹🇷'],
            ['name' => 'Saudi Arabia', 'flag' => '🇸🇦'],
            ['name' => 'United Arab Emirates', 'flag' => '🇦🇪'],
            ['name' => 'Singapore', 'flag' => '🇸🇬'],
            ['name' => 'Hong Kong', 'flag' => '🇭🇰'],
            ['name' => 'Norway', 'flag' => '🇳🇴'],
        ];

        $createdCountries = [];
        foreach ($countries as $countryData) {
            $country = Country::create($countryData);
            $createdCountries[$country->name] = $country->id;
        }

        // Create currencies with country_id
        $currencies = [
            ['name' => 'US Dollar', 'symbol' => '$', 'country' => 'United States'],
            ['name' => 'Euro', 'symbol' => '€', 'country' => 'Germany'],
            ['name' => 'British Pound', 'symbol' => '£', 'country' => 'United Kingdom'],
            ['name' => 'Japanese Yen', 'symbol' => '¥', 'country' => 'Japan'],
            ['name' => 'Canadian Dollar', 'symbol' => 'C$', 'country' => 'Canada'],
            ['name' => 'Australian Dollar', 'symbol' => 'A$', 'country' => 'Australia'],
            ['name' => 'Swiss Franc', 'symbol' => 'Fr', 'country' => 'Switzerland'],
            ['name' => 'Chinese Yuan', 'symbol' => '¥', 'country' => 'China'],
            ['name' => 'Indian Rupee', 'symbol' => '₹', 'country' => 'India'],
            ['name' => 'Mexican Peso', 'symbol' => '$', 'country' => 'Mexico'],
            ['name' => 'Brazilian Real', 'symbol' => 'R$', 'country' => 'Brazil'],
            ['name' => 'South African Rand', 'symbol' => 'R', 'country' => 'South Africa'],
            ['name' => 'Russian Ruble', 'symbol' => '₽', 'country' => 'Russia'],
            ['name' => 'Korean Won', 'symbol' => '₩', 'country' => 'South Korea'],
            ['name' => 'Turkish Lira', 'symbol' => '₺', 'country' => 'Turkey'],
            ['name' => 'Saudi Riyal', 'symbol' => '﷼', 'country' => 'Saudi Arabia'],
            ['name' => 'UAE Dirham', 'symbol' => 'د.إ', 'country' => 'United Arab Emirates'],
            ['name' => 'Singapore Dollar', 'symbol' => 'S$', 'country' => 'Singapore'],
            ['name' => 'Hong Kong Dollar', 'symbol' => 'HK$', 'country' => 'Hong Kong'],
            ['name' => 'Norwegian Krone', 'symbol' => 'kr', 'country' => 'Norway'],
        ];

        foreach ($currencies as $currencyData) {
            $countryName = $currencyData['country'];
            unset($currencyData['country']);
            
            $currencyData['country_id'] = $createdCountries[$countryName];
            Currency::create($currencyData);
        }

        $this->command->info('Countries and currencies seeded successfully!');
    }
}
