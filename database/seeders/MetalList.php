<?php

namespace Database\Seeders;

use App\Models\Metal;
use Illuminate\Database\Seeder;

class MetalList extends Seeder
{
    public function run(): void
    {
        $metals = [
            [
                'ref' => 'Ceramic -001',
                'name' => 'Ceramic',
                'image_url' => '/storage/metals/UwIF4qHQ8nNLIipjfF1menKEUC9zfL9RB9JKIX6T.png',
            ],
            [
                'ref' => 'Fabric-001',
                'name' => 'Fabric',
                'image_url' => '/storage/metals/ZgKDucWBPa8S33jKc0uOs5QlAwqf2egpLavrHkj4.png',
            ],
            [
                'ref' => 'MET-0010',
                'name' => 'Wood',
                'image_url' => '/storage/metals/TR47ZWZ8TZsu1jUnw99IRIOiY0BT4bQnoekjFtvA.jpg',
            ],
        ];

        foreach ($metals as $metal) {
            Metal::updateOrCreate(
                ['name' => $metal['name']],
                $metal
            );
        }
    }
}
