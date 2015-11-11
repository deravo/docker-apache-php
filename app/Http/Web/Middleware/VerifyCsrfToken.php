<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Lumen\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (app()->environment() !== 'testing')
        {
            return parent::handle($request, $next);
        }
        return $next($request);
    }
}
