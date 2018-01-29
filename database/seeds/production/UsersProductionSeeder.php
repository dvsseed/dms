<?php

use Illuminate\Database\Seeder;

class UsersProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$admin = factory(App\User::class, 'admin')->create(['email' => 'first@admin.com']);
        //$this->command->info("New Admin created. Username: $admin->email,  Password: 123pass");

        factory(App\User::class, 'admin')->create(['name' => 'davis','username' => '曾令燊','email' => 'dvsseed@dmclinicyu.com','password' => bcrypt('Fil74!0c'),'bio' => '機構管理者']);
        $admin = factory(App\User::class, 'admin')->create(['name' => 'admin','username' => '系統管理者','email' => 'admin@dmclinicyu.com','password' => bcrypt('Posn395?'),'bio' => 'super系統管理者']);
        $this->command->info("New Admin created. Username: $admin->email,  Password: Posn395?");
    }
}
