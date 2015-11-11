<?php
namespace App\Http\Man\Middleware;

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
        // print_r($request->all());exit;
        if (app()->environment() !== 'local')
        {
            return parent::handle($request, $next);
        }
        return $next($request);
    }
}
