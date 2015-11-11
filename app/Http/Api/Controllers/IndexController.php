<?php
namespace App\Http\Api\Controllers;

use DB;
use App\Http\Api\Controllers\Controller;

//~ 两个加密类
//~ 1st 单向加密
//~ 2nd 双向加解密
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Crypt;
use App;


class IndexController extends Controller
{
    private $hasher;
    private $redis;
    /**
     * @Author:      Alvin
     * @DateTime:    2015-08-26 10:07:18
     * @Description: Construct
     */
    public function __construct(HasherContract $hasher)
    {
        $this->hasher = $hasher;
        $this->redis = App::make('redis');
    }

	public function getIndex()
	{
		return 'this is a test file';
	}

    public function getDistrict()
    {
        $province = DB::select('select district_id, district_name from info_district where district_level = 1');
        foreach($province as $k => $v)
        {
            $province[$k]->children = DB::select('select district_id, district_name from info_district where district_level = 2 and province_id = ' . $v->district_id);
        }
        return response()->json($province);
    }

    /**
     * @Author:      Alvin
     * @DateTime:    2015-08-26 12:46:58
     * @Description: 后台首页
     */
    public function main()
    {
        $user = $this->auth->user();
        return view('index')->withUser($user);
    }

    /**
     * @Author:      name
     * @DateTime:    2015-08-26 12:46:38
     * @Description: Description
     */
    public function test()
    {
        return $this->hasher->make(md5('123456'));
    }
}
