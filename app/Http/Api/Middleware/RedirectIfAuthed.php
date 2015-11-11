<?php namespace App\Http\Api\Middleware;

use App;
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
        /*
        $redis = App::make('redis');
        $conn = $redis->connection('logined_device');
        $checked = json_decode($conn->get($request->get('telephone')));

        if ( $checked && isset($checked->device_id) && $checked->device_id == $request->get('device_id') )
        {
            return json_output(2000, '登录成功', json_decode($redis->connection('logined')->get($checked->user_id)));
        }
        $redis->disconnect();
        */
        return $next($request);
    }

}