<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoryList extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $categories = [
            ['name' => 'Pottery'],
            ['name' => 'Knitting'],
            ['name' => 'Pillow'],
            ['name'=> 'Pouf'] ,
            ['name'=> 'Chair'] ,
            ['name'=> 'Drawer'] ,
            ['name'=> 'Table'] ,
            ['name'=> 'Sofa'] , ];
            foreach($categories as $categorie){ 
                Category::create($categorie);
            }
     
    }
}
