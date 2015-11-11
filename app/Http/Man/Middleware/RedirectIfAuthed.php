<?php
namespace App\Http\Man\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\Middleware;

class RedirectIfAuthed implements Middleware
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
/*
        if ( Auth::check() )
        {
            return redirect()->route('dashboard');
        }
        return $next($request);
*/
        if ( $this->auth->check() )
        {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}