<?php

use Illuminate\Database\Seeder;
use App\Type;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $type = new Type([
            'name' => 'Employee'
            ]);
            
        $type->save();
            
        $type2 = new Type([
            'name' => 'Guest'
        ]);
        $type2->save();
        
        $type3 = new Type([
            'name' => 'HR'
        ]);
        
        $type3->save();
    }
}
