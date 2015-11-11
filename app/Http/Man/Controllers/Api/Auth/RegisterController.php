<?php namespace App\Http\Controllers\Api;

use App\Models\Api\User;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;


class RegisterController extends Controller {

/**
 * App Registration Controller
 */

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Local Api Login Test
	 */
	public function getTelephone(Request $request)
	{
		$result = [
			'telephone'	=> '请正确填写手机号',
			'verify_code' => '验证码错误'
		];
		$validator = Validator::make($request->only(['telephone', 'verify_code']), [
			'telepohne'	=> '',
			'verify_code' => ''
		], $result);

		if ( $validatory->fails() )
		{
			return $result;
		}
	}

	/**
	 * @Author:      Alvin
	 * @DateTime:    2015-10-20 9:56:54
	 * @Description: 验证登录
	 */
	public function postTelephone(Request $request)
	{
		//~ 1st validate method;
		$validator = Validator::make($request->only(['username', 'password']), [
            'username' => 'required',
            'password' => 'required'
        ], [
        	'username' => '账号必须填写',
        	'password' => '密码必须填写'
        ]);
        //~ 2nd validate method;
        //~ $this->validate($request, ['username' => 'required', 'password' => 'required']);

        if ( $validator->fails() )
        {
        	return redirect()->back()->withErrors($validator->getCustomMessages())->withInput();
        }
        else {
        	$credentials = $request->only('username', 'password');
        	if ($this->auth->attempt($credentials, $request->has('remember')))
			{
				return redirect($this->redirectPath());
			}

			return redirect($this->loginPath())
					->withInput($request->only('username', 'remember'))
					->withErrors([
						'用户名或密码错误',
					]);
        }
	}

	/**
	 * Get the post register / login redirect path.
	 *
	 * @return string
	 */
	public function redirectPath()
	{
		if (property_exists($this, 'redirectPath'))
		{
			return $this->redirectPath;
		}

		return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
	}

	/**
	 * @Author:      Alvin
	 * @DateTime:    2015-08-25 15:52:02
	 * @Description: 退出登录
	 */
	public function getLogout()
	{
		$this->auth->logout();
		return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/auth/login');
	}

	/**
	 * Get the path to the login route.
	 *
	 * @return string
	 */
	public function loginPath()
	{
		return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
	}

}

