<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('roles')->truncate();

        $member = [
            'name' => 'Member',
            'level' => 1
        ];
        $cde = [
            'name' => 'Diabetes Educator',
            'level' => 2
        ];
        $doctor = [
            'name' => 'Doctor',
            'level' => 3
        ];
        $moderator = [
            'name' => 'Moderator',
            'level' => 5
        ];
        $sModerator = [
            'name' => 'Super Moderator',
            'level' => 7
        ];
        $admin = [
            'name' => 'Admin',
            'level' => 9
        ];
        
        factory(App\Role::class)->create($admin);
        factory(App\Role::class)->create($member);
        factory(App\Role::class)->create($cde);
        factory(App\Role::class)->create($doctor);
        factory(App\Role::class)->create($moderator);
        factory(App\Role::class)->create($sModerator);
    }
}
