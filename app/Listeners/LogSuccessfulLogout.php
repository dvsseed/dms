<?php

namespace App\Listeners;

use Auth;
use DB;

use App\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogout
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
     * @param  Logout  $event
     * @return void
     */
//    public function handle(Logout $event)
// Remove Logout $event as a paremeter in handle() method
    public function handle()
    {
        //To record user's logout log
        DB::table('logs')->insert([
            'table'   => '無',
            'action'  => 'Logout(登出)',
            'user_id' => Auth::user()->id,
            'ip'      => \Request::ip(),
            'useragent'  => \Request::header('User-Agent'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
