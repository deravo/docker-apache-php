<?php
namespace App\Http\Man\Controllers;

use App;
use Auth;
use App\Models\Man\Manager;
use Illuminate\Http\Request;
use App\Http\Man\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
// use App\Libraries\Role;

//~ 两个加密类
//~ 1st 单向加密
//~ 2nd 双向加解密
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Crypt;


class IndexController extends Controller
{
    private $hasher;
    private $auth;
    /**
     * @Author:      Alvin
     * @DateTime:    2015-08-26 10:07:18
     * @Description: Construct
     */
    public function __construct(HasherContract $hasher)
    {
        $this->hasher = $hasher;

        $this->middleware('man.auth');
    }

    /**
     * @Author:      Alvin
     * @DateTime:    2015-08-26 12:46:58
     * @Description: 后台首页
     */
    public function man(Request $request)
    {
        //$user = $this->auth->getUser();
        $user = $request->user();
        //print_r($user->toArray());exit;
        return view('man.index')->withUser($user);
    }

    /**
     * @Author:      name
     * @DateTime:    2015-08-26 12:46:38
     * @Description: Description
     */
    public function test()
    {
        return $this->hasher->make('123456');
    }
}