<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Store;
use Illuminate\Http\Response;

class SessionTimeout
{

    protected $session;
    protected $timeout = 1200;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isLoggedIn = $request->path() != 'logout';
        if(! session('lastActivityTime'))
            $this->session->put('lastActivityTime', time());
        else if(time() - $this->session->get('lastActivityTime') > $this->timeout)
        {
            $this->session->forget('lastActivityTime');
//            $cookie = cookie('intend', $isLoggedIn ? url()->current() : 'dashboard');
//            $email = $request->user()->email;
            auth()->logout();
            // Call to undefined function
            // return message('You had not activity in '. $this->timeout/60 .' minutes ago.', 'warning', 'login')->withInput(compact('email'))->withCookie($cookie);
            $content = 'You had not activity in '. $this->timeout / 60 .' minutes ago. Please refresh the page and try again.';
            $status = 200;
            $value = 'text/html;charset=utf-8';
            // return (new Response($content, $status))->header('Content-Type', $value)->withInput(compact('email'))->withCookie($cookie);
            return (new Response($content, $status))->header('Content-Type', $value);
        }

        $isLoggedIn ? $this->session->put('lastActivityTime', time()) : $this->session->forget('lastActivityTime');
        return $next($request);
    }

}
