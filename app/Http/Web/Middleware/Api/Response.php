<?php
namespace App\Http\Middleware\Api;

use Closure;

class Response
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if ( $response->status() == 200 )
        {
            return json_encode($response->original);
        }
        else {
            return json_encode($response->exception);
        }
    }
}
