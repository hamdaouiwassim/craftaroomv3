<?php

namespace Database\Seeders;

use App\Models\Metal;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MetalList extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $metals = [
            ['name' => 'Wood'],
            ['name' => 'Metal'],
            ['name'=> 'Fabric'] ,
            ['name'=> 'Ceramic'] ,
            ['name'=> 'Fronds'] ,
            ];
            foreach($metals as $metal){ 
                Metal::create($metal);
            }
    }
}
