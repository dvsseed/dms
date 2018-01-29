<?php

namespace App\Listeners;

use Auth;
use DB;
use Carbon\Carbon;

use App\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
//    public function handle(Login $event)
// Remove Logout $event as a paremeter in handle() method
    public function handle()
    {
        //To record user's login log
        DB::table('logs')->insert([
            'table'   => 'logs',
            'action'  => 'Login(登入)',
            'user_id' => Auth::user()->id,
            'ip'      => \Request::ip(),
            'useragent' => \Request::header('User-Agent'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
