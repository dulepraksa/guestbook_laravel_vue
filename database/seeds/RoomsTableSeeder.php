<?php

use Illuminate\Database\Seeder;
use App\Room;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $room1 = new Room([
            'name' => 'MR1'
        ]);

        $room1->save();

        $room2 = new Room([
            'name' => 'MR2'
        ]);

        $room2->save();

        $room3 = new Room([
            'name' => 'MR3'
        ]);

        $room3->save();
    }
}
