<?php
namespace App\Http\Api\Middleware;

use App;
use Auth;
use Closure;
use Illuminate\Http\Request;

class Authenticate
{
	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $redis;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct()
	{
		$this->redis = App::make('redis');
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
        $authInfo = [
            'user_id'   => $request->header('user-id'),
            'device_id' => $request->header('device-id')
        ];
        $userInfo = json_decode($this->redis->connection('logined')->get($authInfo['user_id']));
        if ( ! $userInfo )
        {
            return json_output(401, '请重新登录');
        }
        else {
            $deviceInfo = json_decode($this->redis->connection('logined_device')->get($userInfo->telephone));
            if ( !$deviceInfo || $deviceInfo->device_id != $authInfo['device_id'] )
            {
                return json_output(401, '请重新登录');
            }
        }
        return $next($request);
    }
}
