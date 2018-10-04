<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('institution', 100)->nullable();
            $table->string('hosp_id', 10)->nullable();
            $table->string('name', 50);
            $table->string('username', 20)->unique();
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->string('bio', 100)->nullable();
            $table->string('pid', 30)->nullable();
            // $table->dateTime('last_login')->nullable();
            // $table->string('ip_address', 15)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
