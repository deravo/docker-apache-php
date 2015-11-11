<?php
namespace App\Http\Man\Middleware;

use Closure;
//use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\Middleware;

class Authenticate implements Middleware
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if ( !$this->auth->check() )
    	{
    		if ( $request->ajax() )
    		{
                return response(view("errors.401"), 401);
    		}
    		else {
    			return redirect()->route('getLogin');
    		}
    	}
    	return $next($request);
    }
}
