<?php

namespace Database\Seeders;

use App\Models\Metal;
use Illuminate\Database\Seeder;

class MetalOptionList extends Seeder
{
    public function run(): void
    {
        $optionsByMetal = [
            'Ceramic' => [
                [
                    'ref' => 'Ceramic -001',
                    'name' => 'Ceramic khozaf',
                    'image_url' => '/storage/metals/options/M9cUuEJKVNbFjQVMH2UCre0xGxqSxeQt3MK0eAIu.jpg',
                ],
                [
                    'ref' => 'Ceramic -002',
                    'name' => 'Ceramic Tertaf',
                    'image_url' => '/storage/metals/options/SKUwHuWE5R5ipYmsK3dhYpLdfgKanIadLXs8fbYZ.jpg',
                ],
                [
                    'ref' => 'Ceramic -003',
                    'name' => 'Ceramic andaloss',
                    'image_url' => '/storage/metals/options/xKyJZxaIP8BOl7ZYqzsugd80Z38GFtK6RTSGqfKY.jpg',
                ],
            ],
            'Fabric' => [
                [
                    'ref' => 'MET-0014',
                    'name' => 'Djeans',
                    'image_url' => '/storage/metals/options/SMRudPXfOcFyUHruZAwVEYbzK5XHz80MUYuy2CbK.jpg',
                ],
                [
                    'ref' => 'MET-0015',
                    'name' => 'Cotton',
                    'image_url' => '/storage/metals/options/iGF3NvRt54KzwoCA1U6JRRElDcl0VMDVJp8d94OT.jpg',
                ],
                [
                    'ref' => 'MET-0016',
                    'name' => 'bny',
                    'image_url' => '/storage/metals/options/ZQ58mXMw1Cb8tDzsHqDALspmZkJefMBzVCQKrIxI.jpg',
                ],
            ],
            'Wood' => [
                [
                    'ref' => 'MET-0011',
                    'name' => 'MDF',
                    'image_url' => '/storage/metals/options/7aqOzWodxbKNazF5T3JjJwI7G5OnzLe13kIn0lil.png',
                ],
                [
                    'ref' => 'MET-0012',
                    'name' => 'jlk',
                    'image_url' => '/storage/metals/options/vrArx9WBFIWJGNUI1UqPIxJX3gOyThKZPDLlCoUp.jpg',
                ],
                [
                    'ref' => 'MET-0013',
                    'name' => 'mkl',
                    'image_url' => '/storage/metals/options/U0BQxPtynb5Cv0RNScvhKDd6o4u4hgofmFwO9XbL.jpg',
                ],
            ],
        ];

        foreach ($optionsByMetal as $metalName => $options) {
            $metal = Metal::where('name', $metalName)->first();

            if (!$metal) {
                continue;
            }

            foreach ($options as $option) {
                $metal->metalOptions()->updateOrCreate(
                    ['name' => $option['name']],
                    $option
                );
            }
        }
    }
}
