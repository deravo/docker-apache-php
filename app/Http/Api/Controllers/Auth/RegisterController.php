<?php namespace App\Http\Api\Controllers\Auth;

use App;
use App\Http\Api\Controllers\Controller;

use App\Models\Api\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use Validator;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Crypt;

use App\Libraries\Sms\SmsContract;

use Cache;
use DB;
//use Illuminate\Contracts\Redis\Database as Redis;
use Illuminate\Contracts\Redis\Database as RedisContract;

class RegisterController extends Controller
{
    private $hasher;
    private $auth;
    private $sms;
    private $redis;
    /**
     * @Author:      Alvin
     * @DateTime:    2015-08-26 10:07:18
     * @Description: Construct
     */
    public function __construct(HasherContract $hasher, Guard $auth, SmsContract $sms)
    {
        $this->hasher = $hasher;
        $this->auth = $auth;
        $this->sms = $sms;
        $this->redis = App::make('redis');
    }


/**
 * @Author:      Alvin
 * @Email:       jin@aliuda.com
 * @DateTime:    2015-10-28 14:07:37
 * @Description: 验证手机号并发送验证码
 */

    public function postCheckPhone(Request $request)
    {
        $validator = Validator::make(
            $request->only(['telephone']),
            [
                'telephone' => ["regex:/(^(1[3-9])[0-9]{9}$)|(^(010|02[0-9]|0[3-9][0-9]{2})(-)?[1-9][0-9]{6,7}$)/"],
                'telephone' => 'unique:info_user,telephone'
            ],
            [
                'telephone' => '手机号码必须正确填写',
                'telephone' => '该号码已经注册会员，请直接登录'
            ]
        );
        //$res = DB::select("select count(*) as agg from info_user where `telephone` = '13572429270'")[0]->agg;
        if  ( $validator->fails() )
        {
            return json_output(2001, $validator->getCustomMessages()['telephone']);
        }
        else {
            $VerifyCode = randomInt(6);
            $sendFlag = $this->_checkVerifyCode($request->get('telephone'), $VerifyCode);
            if ( $sendFlag[0] === true )
            {
                $this->sms->loadConfig('sms');
                if (
                    $this->sms->send($request->get('telephone'), '【当老师】正在身份认证，验证码' . $VerifyCode . '，有效期30分钟')
                ) {
                    return json_output(2000, '验证码已发送，请查看您的短信', $VerifyCode);
                }
                return json_output(2001, '验证码发送失败，请稍后重试', $VerifyCode);
            }
            return json_output(2002, $sendFlag[1]);
        }
    }

/**
 * @Author:      Alvin
 * @Email:       jin@aliuda.com
 * @DateTime:    2015-10-28 13:18:35
 * @Description: 验证手机验证码的正确性
 */
    
    public function postVerifyCode(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'telephone' => ["regex:/(^(1[3-9])[0-9]{9}$)|(^(010|02[0-9]|0[3-9][0-9]{2})(-)?[1-9][0-9]{6,7}$)/"],
                'telephone' => 'unique:info_user,telephone',
                'verify_code'   => "regex:/^\d{6}$/"
            ],
            [
                'telephone' => '手机号码必须正确填写',
                'telephone' => '该号码已经注册会员，请直接登录',
                'verify_code' => '验证码错误，如果未收到短信请稍后重新获取'
            ]
        );

        if ( $validator->fails() )
        {
            return json_output(2001, $validator->getCustomMessages()[array_keys($validator->invalid())[0]]);
        }
        else {
            $cachedVal = json_decode($this->redis->get($request->get('telephone')));
            if  ( !$cachedVal )
            {
                return json_output(2002.1, '验证失败：无效的电话号码，请重新获取验证码');
            }
            else {
                if ( $cachedVal->value != $request->get('verify_code') )
                {
                    return json_output(2002.2, '验证失败：验证码失效，请重新获取');
                }
            }
            return json_output(2000, '验证通过');
        }
    }


/**
 * @Author:      Alvin
 * @Email:       jin@aliuda.com
 * @DateTime:    2015-10-21 10:29:04
 * @Description: 注册第二步，设置密码并保存注册信息，成功后返回新用户信息
 */
    public function postSetPassword(Request $request)
    {

        $apiTest = $request->server('HTTP_HOST') == env('APP_DEBUG_HOST') ? true : false;

        //~ 再次验证，做不信任处理
        $validator = Validator::make(
            $request->all(),
            [
                'telephone' => ["regex:/(^(1[3-9])[0-9]{9}$)|(^(010|02[0-9]|0[3-9][0-9]{2})(-)?[1-9][0-9]{6,7}$)/"],
                'telephone' => 'unique:info_user,telephone',
                'password'  => $apiTest ? 'required' : 'required|size:32'
            ],
            [
                'telephone' => '手机号码必须正确填写',
                'telephone' => '该号码已经注册会员，请直接登录',
                'password'  => '密码必须填写'
            ]
        );

        if ( $validator->fails() )
        {
            return json_output(2001, $validator->getCustomMessages()[array_keys($validator->invalid())[0]]);
        }

        $cachedVal = json_decode($this->redis->get($request->get('telephone')));
        if  ( !$cachedVal )
        {
            return json_output(2002.1, '未验证的手机号');
        }
        else {
            $overTime = $apiTest ? 7200 : 1800;
            if ( (time() - $cachedVal->time) > $overTime )
            {
                return json_output(2002.2, '验证码过期，请重新获取验证码');
            }
        }

        if ( $request->server('HTTP_HOST') == env('APP_DEBUG_HOST'))
        {
            $password = $this->hasher->make(strtolower(md5($request->get('password'))));
        }
        else {
            $password = $this->hasher->make(strtolower($request->get('password')));
        }
/*
        print_r([
            'original' => $request->get('password'), 
            'md5' => md5($request->get('password')), 
            'hash' => $this->hasher->make(md5($request->get('password'))),
            'hashMD5' => $this->hasher->make($request->get('password'))
        ]);exit;
*/
        $user = new USER;
        $user->telephone = $request->get('telephone');
        $user->password = $password;
        $user->created_time = date('Y-m-d H:i:s');
        $user->reg_device_id = $request->get('device_id');
        $user->reg_device_type = (int)$request->get('device_type');
        $user->status = 1;
        $user->save();
        $this->redis->del($request->get('telephone'));
        $user->province = "";
        $user->district = "";
        $this->redis->connection('logined')->set($user->user_id, json_encode($user));
        $this->redis->connection('logined_device')->set($user->telephone, json_encode(['user_id' => $user->user_id, 'time' => time(), 'device_id' => $request->get('device_id')]));

        return json_output(2000, '注册成功', $user);
    }


/**
 * @Author:      Alvin
 * @Email:       jin@aliuda.com
 * @DateTime:    2015-10-28 13:33:16
 * @Description: 重复申请验证码检测
 */
    
    private function _checkVerifyCode($targetPhone, $VerifyCode)
    {
        //~ 获取注册手机号的短信发送记录
        $cachedVal = json_decode($this->redis->get($targetPhone));
        //~ 是否需要发送短信，默认不发送
        $sendFlag = [false, '不支持的频繁请求'];
        if ( is_object($cachedVal) )
        {
            if ( ( time() - $cachedVal->time ) > 60 )
            {
                $sendFlag[0] = true;
                //~ 更新记录
                $this->redis->set($targetPhone, json_encode(['time' => time(), 'value' => $VerifyCode]));
            }
            else {
                $sendFlag[1] .= ":" . $cachedVal->value;
            }
        }
        else {
            $sendFlag[0] = true;
            $sendFlag[1] = '新发送哦';
            $this->redis->set($targetPhone, json_encode(['time' => time(), 'value' => $VerifyCode]));
        }
        return $sendFlag;
    }
}
