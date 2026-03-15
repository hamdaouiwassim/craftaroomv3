<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryList extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Pottery',
                'description' => 'Pottery products',
                'type' => 'main',
                'status' => 'active',
                'category_id' => null,
            ],
            [
                'name' => 'Knitting',
                'description' => 'Knitting products',
                'type' => 'main',
                'status' => 'active',
                'category_id' => null,
            ],
            [
                'name' => 'Pillow',
                'description' => 'Pillow products',
                'type' => 'main',
                'status' => 'active',
                'category_id' => null,
            ],
            [
                'name' => 'Pouf',
                'description' => 'Pouf products',
                'type' => 'main',
                'status' => 'active',
                'category_id' => null,
            ],
            [
                'name' => 'Chair',
                'description' => 'Chair products',
                'type' => 'main',
                'status' => 'active',
                'category_id' => null,
            ],
            [
                'name' => 'Drawer',
                'description' => 'Drawer products',
                'type' => 'main',
                'status' => 'active',
                'category_id' => null,
            ],
            [
                'name' => 'Table',
                'description' => 'Table products',
                'type' => 'main',
                'status' => 'active',
                'category_id' => null,
            ],
            [
                'name' => 'Sofa',
                'description' => 'Sofa products',
                'type' => 'main',
                'status' => 'active',
                'category_id' => null,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                [
                    'name' => $category['name'],
                    'type' => $category['type'],
                ],
                $category
            );
        }
    }
}
