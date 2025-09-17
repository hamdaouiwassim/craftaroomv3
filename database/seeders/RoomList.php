<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomList extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $rooms = [
            ['name' => 'Livingroom'],
            ['name' => 'Dining Room'],
            ['name' => 'BedRoom'],
            ['name'=> 'Kitchen'] ,
            ['name'=> 'BathRoom'] ,
            ['name' => 'OfficeRoom'],
          
            ];
            foreach($rooms as $room){ 
                Room::create($room);
            }
    }
}
