<?php

use App\Type;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::enableForeignKeyConstraints();

        $this->call(TypesTableSeeder::class);
        $this->call(RoomsTableSeeder::class);
        $this->call(RolesAndPermissionsTableSeeder::class);

        Schema::disableForeignKeyConstraints();
    }
}
