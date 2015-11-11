<?php namespace App\Http\Api\Controllers\Auth;

use App\Models\Api\User;
use App\Http\Api\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Crypt;
//use Cache;
use DB;

class AuthController extends Controller
{
	private $hasher;
    private $redis;

	public function __construct(HasherContract $hasher)
	{
		$this->hasher = $hasher;
		//$this->redis = App::make('redis');
		$this->redis = App::make('redis');
	}

	/**
	 * @Author:      Alvin
	 * @DateTime:    2015-08-25 15:52:26
	 * @Description: 验证登录
	 */
	public function postLogin(Request $request)
	{
		$validator = Validator::make($request->only(['telephone', 'password', 'device_id']), [
            'telephone' => ["regex:/(^(1[3-9])[0-9]{9}$)|(^(010|02[0-9]|0[3-9][0-9]{2})(-)?[1-9][0-9]{6,7}$)/"],
            'password' => 'required',
            'device_id' => 'required'
        ], [
        	'telephone' => '账号是您的手机号且必须填写',
        	'password' => '密码必须填写',
        	'device_id' => '未知设备'
        ]);

        if ( $validator->fails() )
        {
        	$errorKey = array_keys($validator->invalid());
        	$errorMessage = $validator->getCustomMessages()[array_keys($validator->invalid())[0]];
        	return json_output(2001, $errorMessage);
        }
        else {
        	$credentials = $request->only('telephone', 'password');
        	if ( $user = User::where('telephone', $request->get('telephone'))->get()->first() ) 
        	{
        		return json_output(2002, '未注册的用户');
        	}

        	if ( !$this->hasher->check(strtolower($request->get('password')), $user->password) )
        	{
        		return json_output(2003, '密码错误');
        	}
        	else {
                $user->device_id = $request->get('device_id');
                $user->save();
        		if ( $user->province_id > 0 && !isset($user->province) )
        		{
			        $province = DB::select("select district_id, district_name from info_district where district_id in (?, ?)", [(int)$user->province_id, (int)$user->district_id]);
			        if ( count($province) == 2 )
			        {
			            $user->district = $province[1]->district_name;
			            $user->province = $province[0]->district_name;
			        }
        		}

                $this->redis->connection('logined')->set($user->user_id, json_encode($user));
        		$this->redis->connection('logined_device')->set($user->telephone, json_encode(['user_id' => $user->user_id, 'time' => time(), 'device_id' => $request->get('device_id')]));
        		return json_output(2000, '登录成功', $user);
        	}
        }
	}

	/**
	 * @Author:      Alvin
	 * @DateTime:    2015-08-25 15:52:02
	 * @Description: 退出登录
	 */
	public function getLogout(Request $request)
	{
		$validator = Validator::make(
            $request->only('telephone'),
            [
                'telephone' => ["regex:/(^(1[3-9])[0-9]{9}$)|(^(010|02[0-9]|0[3-9][0-9]{2})(-)?[1-9][0-9]{6,7}$)/", "required"]
            ], [
        	    'telephone' => '系统繁忙'
            ]
        );


		if ( $validator->fails() )
		{
            $authInfo = [
                'user_id'   => $request->header('user-id'),
                'device_id' => $request->header('device-id')
            ];
            if ( $userInfo = json_decode($this->redis->connection('logined')->get($authInfo['user_id'])) )
            {
                $this->redis->connection('logined')->del($authInfo['user_id']);
                $this->redis->connection('logined_device')->del($userInfo->telephone);
                return json_output(2000, '退出成功');
            }
            else {
                $errorKey = array_keys($validator->invalid());
                $errorMessage = $validator->getCustomMessages()[array_keys($validator->invalid())[0]];
                return json_output(2001, $errorMessage);
            }
		}
		else {
			if ( $device = json_decode($this->redis->connection('logined_device')->get($request->get('telephone'))) )
			{
                $this->redis->connection('logined')->del($device->user_id);
                $this->redis->connection('logined_device')->del($request->get('telephone'));
                return json_output(2000, '退出成功');
			}
			return json_output(2000, '没有登录信息');
		}
	}


/**
 * @Author:      Alvin
 * @Email:       jin@aliuda.com
 * @DateTime:    2015-10-31 18:26:38
 * @Description: 修改密码
 */

	public function postModifyPassword(Request $request)
	{
		$validator = Validator::make($request->only(['old_password', 'new_password']), [
            'old_password' => 'required',
            'new_password' => 'required'
        ], [
        	'old_password' => '原始密码必须提供',
        	'new_password' => '原始密码必须提供'
        ]);

        if ( $validator->fails() )
        {
        	$errorMessage = $validator->getCustomMessages()[array_keys($validator->invalid())[0]];
        	return json_output(2001, $errorMessage);
        }
        else {
        	if ( $request->get('old_password') == $request->get('new_password') )
        	{
        		return json_output(2002, '新密码不能和原密码相同');
        	}
        }

        $user = User::find((int)$request->header("user-id"));
        if ( $user )
        {
        	$user->password = $this->hasher->make(strtolower($request->get('new_password')));
        	if ( $user->save() )
        	{
        		return json_output(2000, '更新成功');
        	}
        	else {
        		return json_output(2001, '更新失败');
        	}
        }

	}

}

