<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;

class RolesAndPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role_visitor = Role::create(['name' => 'Visitor']);
        $role_gdpr = Role::create(['name' => 'GDPR-Admin']);
        $role_secretary = Role::create(['name' => 'Secretary']);
        $role_staff = Role::create(['name' => 'Staff']);
        $role_administrator = Role::create(['name' => 'Administrator']);
        
        
        $user_staff = new User ();
        $user_staff->type_id = 3;
        $user_staff->name = 'Djordje';
        $user_staff->surname = 'Meandžija';
        $user_staff->email = 'Djordje.Meandzija@tiacgroup.com';
        $user_staff->password = bcrypt('DjordjeMeandzija');
        $user_staff->save();
        $user_staff->assignRole('Secretary');


        $user_secretary = new User();
        $user_secretary->name = 'Emilija';
        $user_secretary->surname = 'Čizmar';
        $user_secretary->type_id = 1;
        $user_secretary->email = 'emilija.cizmar@tiacgroup.com';
        $user_secretary->password = bcrypt('EmilijaCizmar');
        $user_secretary->save();
        $user_secretary->assignRole('Secretary');

        $user_gdpr = new User();
        $user_gdpr->type_id = 1;
        $user_gdpr->name = 'Vladimir';
        $user_gdpr->surname = 'Mandić';
        $user_gdpr->email = 'vladimir.mandic@tiacgroup.com';
        $user_gdpr->password = bcrypt('VladimirMandic');
        $user_gdpr->save();
        $user_gdpr->assignRole('Administrator');


        $user_administrator = new User();
        $user_administrator->name = 'Admin';
        $user_administrator->type_id = 1;
        $user_administrator->surname = 'Administratovic';
        $user_administrator->email = 'admin@gmail.com';
        $user_administrator->password = bcrypt('admin');
        $user_administrator->save();
        $user_administrator->assignRole('Administrator');


        $gdpr = new User();
        $gdpr->name = "GDPR";
        $gdpr->surname = "GDPR";
        $gdpr->type_id = 1;
        $gdpr->email = 'gdpr@gdpr.com';
        $gdpr->password = bcrypt('TIACGdpr');
        $gdpr->save();
        $gdpr->assignRole('GDPR-Admin');
    }
}