<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Concept;
use App\Models\ConstructionRequest;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetCategoriesToDefaultsSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            ConstructionRequest::query()->delete();

            Media::query()
                ->whereIn('type', ['product', 'threedmodel', 'concept', 'concept_threedmodel', 'category'])
                ->delete();

            Product::query()->delete();
            Concept::query()->delete();
            Category::query()->delete();

            $this->call(CategoryList::class);
        });
    }
}
