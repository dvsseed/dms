<?php

namespace App\Providers;

//use Auth;
//use DB;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],

        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],

//        'Illuminate\Auth\Events\Logout' => [
//            'App\Listeners\LogSuccessfulLogout',
//        ],

        'Illuminate\Auth\Events\Lockout' => [
            'App\Listeners\LogLockout',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //To record user's login log
//        $events->listen('auth.login', function ($user, $remember) {
//            $user->update(['last_login' => \Carbon\Carbon::now(), 'ip' => \Request::ip());
//        });
//        $log = new Log;
//        $log->table = '';
//        $log->action = 'Login(登入)';
//        $log->user_id = Auth::user()->id;
//        $log->ip = \Request::ip();
//        $log->save();
//        $events->listen('auth.login', function()
//        {
//            DB::table('logs')
//                ->where('id', Auth::id())
//                ->update(array(
//                    'last_login' => date('Y-m-d H:i:s')
//                ));
//            DB::table('logs')->insert([
//                'table'   => '無',
//                'action'  => 'Login(登入)',
//                'user_id' => Auth::id(),
//                'ip'      => \Request::ip()
//            ]);
//        });

    }
}
