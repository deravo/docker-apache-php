<?php
namespace App\Http\Error\Controllers;

use App\Http\Error\Controllers\Controller;

//~ 两个加密类
//~ 1st 单向加密
//~ 2nd 双向加解密
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Crypt;


class IndexController extends Controller
{

    /**
     * @Author:      Alvin
     * @Email:       jin@aliuda.com
     * @DateTime:    2015-10-20 15:17:38
     * @Description: 默认访问入口 
     */
    
	public function index()
	{
		return 'yes, you have found it!';
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
