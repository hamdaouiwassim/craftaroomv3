<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            ['name' => 'Salon'],
            ['name' => 'Chambre principale'],
            ['name' => 'Chambre d\'amis'],
            ['name' => 'Chambre d\'enfant'],
            ['name' => 'Salle à manger'],
            ['name' => 'Cuisine'],
            ['name' => 'Salle de bain'],
            ['name' => 'Salle de bain principale'],
            ['name' => 'Salle de bain d\'amis'],
            ['name' => 'Bureau'],
            ['name' => 'Entrée'],
            ['name' => 'Couloir'],
            ['name' => 'Véranda'],
            ['name' => 'Terrasse'],
            ['name' => 'Balcon'],
            ['name' => 'Garage'],
            ['name' => 'Cave'],
            ['name' => 'Grenier'],
            ['name' => 'Buanderie'],
            ['name' => 'Dressing'],
            ['name' => 'Bibliothèque'],
            ['name' => 'Salle de jeu'],
            ['name' => 'Salle de sport'],
            ['name' => 'Studio'],
            ['name' => 'Jardin'],
            ['name' => 'Piscine'],
            ['name' => 'Spa'],
            ['name' => 'Sauna'],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        $this->command->info('Rooms seeded successfully!');
    }
}
