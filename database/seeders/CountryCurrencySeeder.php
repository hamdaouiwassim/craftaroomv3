<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CountryCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = resource_path('files/countries.json');

        if (!File::exists($path)) {
            $this->command->error("Countries data file not found.");
            return;
        }

        $countriesData = json_decode(File::get($path), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($countriesData)) {
            $this->command->error('Invalid JSON format or data structure.');
            return;
        }

        $countryCount = 0;
        $currencyCount = 0;
        $skippedCurrencyCount = 0;

        // Use a database transaction to ensure atomicity (all or nothing)
        DB::transaction(function () use ($countriesData, &$countryCount, &$currencyCount, &$skippedCurrencyCount) {

            foreach ($countriesData as $item) {
                // 1. Insert the Country and retrieve its new primary key (ID)
                $country = Country::create([
                    'name' => $item['name'],
                    'flag' => $item['flag'],
                    // Add other country fields
                ]);
                $countryCount++;

                // 2. CHECK FOR CURRENCY DATA
                // We ensure 'currency' key exists AND it's not null/empty
                if (isset($item['currency']) && is_array($item['currency'])) {
                    // 3. Use the retrieved ID to create the Currency record
                    $currency = Currency::create([
                        'country_id' => $country->id, // <== Foreign Key
                        'name'       => $item['currency']['name'],
                        'symbol'     => $item['currency']['symbol'],
                    ]);
                    $currencyCount++;
                } else {
                    $skippedCurrencyCount++;
                    $this->command->warn("Skipped currency for country: {$item['name']} (missing data).");
                }
            }
        });

        $this->command->info("\nData import successful:");
        $this->command->info("- **{$countryCount}** countries inserted.");
        $this->command->info("- **{$currencyCount}** currency records inserted.");
        if ($skippedCurrencyCount > 0) {
            $this->command->warn("- **{$skippedCurrencyCount}** currency records skipped (null/missing data).");
        }
    }
}
