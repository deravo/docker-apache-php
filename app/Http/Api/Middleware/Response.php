<?php
namespace App\Http\Api\Middleware;

use Closure;
use Log;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Crypt;

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
        //这里开始判断来源是否是APP
        //$server = $request->server();
        if ( !env('APP_DEBUG') )
        {
            // if ( $server['REDIRECT_HTTPS'] != 'on' || $server['HTTPS'] != 'on' )
            // {
                // return;
            // }
            /*
            $app = Crypt::decrypt($request->header('application'));
            if ( !is_array($app) || !isset($app['bundle_id']) )
            {
                
            }
            */
        }

        if ( env('APP_LOG') )
        {
            Log::info(json_encode(['ajax' => $request->ajax() ? 'true' : 'false', 'header' => $request->header(), 'body' => $request->all(), 'path' => $request->getPathInfo()]));
        }

/*
        $enc = Crypt::encrypt(array(
            'bundle_id'    => Crypt::hash('', ''),
            'version' => '1.1.2'
        ));
        print_r(Crypt::decrypt($enc));exit;
        echo Crypt::hash('abc', '123');exit;

        print_r($postHeader);exit;
*/
        //before
        $response = $next($request);

        if ( $response instanceof Closure )
        {
            //after
            if ( $response->status() == 200 )
            {
                return $response->original;
            }
            return $response->exception;
        }
        else {
            return $response;
        }
    }

    public function terminate($request, $response)
    {
        
    }
}
