<?php namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthed {

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check())
        {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}