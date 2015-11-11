<?php
namespace App\Http\Man\Controllers\Auth;

use Validator;
use App\Models\Man\Manager;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Man\Controllers\Controller;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	private $redirectPath = '/';

	private $redirectAfterLogout = "/auth/login";

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
		$this->middleware('man.guest', ['except' => 'getLogout']);
	}

	/**
	 * @Author:      Alvin
	 * @DateTime:    2015-08-25 15:50:51
	 * @Description: 显示登录页面
	 */
	public function getLogin(Request $request)
	{
		return view('man.login');
	}

	/**
	 * @Author:      Alvin
	 * @DateTime:    2015-08-25 15:52:26
	 * @Description: 验证登录
	 */
	public function postLogin(Request $request)
	{
		$validator = Validator::make($request->only(['username', 'password']), [
            'username' => 'required',
            'password' => 'required'
        ], [
        	'username' => '账号必须填写',
        	'password' => '密码必须填写'
        ]);

        if ( $validator->fails() )
        {
        	return redirect()->back()->withErrors($validator->getCustomMessages())->withInput();
        }
        else {
        	$credentials = $request->only('username', 'password');
        	if ( $this->auth->attempt($credentials, $request->has('remember')) )
			{
				return redirect('/');
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

