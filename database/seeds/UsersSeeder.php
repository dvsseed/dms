<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        //factory(App\User::class, 2)->create();
        //診所同仁
        $user = factory(App\User::class)->create(['name' => 'davis','hosp_id' => '3534021927','username' => '曾令燊','email' => 'dvsseed@dmclinicyu.com','password' => bcrypt('Fil74!0c'),'bio' => '機構管理者','role_level' => 7]);
        factory(App\User::class)->create(['name' => 'dm9556670','hosp_id' => '3534021927','username' => '游能俊','email' => 'dm9556670@gmail.com','password' => bcrypt('501022'),'bio' => '診所醫師','role_level' => 3]);
        factory(App\User::class)->create(['name' => 'tracy','hosp_id' => '3534021927','username' => '李怡慧','email' => 'tracylee.dm@gmail.com','password' => bcrypt('Dm556670'),'bio' => '診所衛教師','role_level' => 2]);
        factory(App\User::class)->create(['name' => 'yvanne11','hosp_id' => '3534021927','username' => '邱奕映','email' => 'yvannw1009@gmail.com','password' => bcrypt('yvanne11'),'bio' => '診所衛教師','role_level' => 2]);
        factory(App\User::class)->create(['name' => 'fish','hosp_id' => '3534021927','username' => '吳婉瑜','email' => 'fish_30013@yahoo.com.tw','password' => bcrypt('fish'),'bio' => '診所衛教師','role_level' => 2]);
        factory(App\User::class)->create(['name' => 'kitty','hosp_id' => '3534021927','username' => '黃琬婷','email' => 'anne_0826@yahoo.com.tw','password' => bcrypt('kitty'),'bio' => '診所衛教師','role_level' => 2]);
        factory(App\User::class)->create(['name' => 'user','hosp_id' => '3534021927','username' => '測試使用者','email' => 'user@dmclinicyu.com','password' => bcrypt('Pea42u6$'),'bio' => '診所使用者','role_level' => 1]);

        $admin = factory(App\User::class, 'admin')->create(['name' => 'admin','username' => '系統管理者','email' => 'admin@dmclinicyu.com','password' => bcrypt('Posn395?'),'bio' => 'super系統管理者']);

        $this->command->info("New Admin created. Username: $admin->email,  Password: Posn395?");
        $this->command->info("New Admin created. Username: $user->email,  Password: Fil74!0c");
    }
}
