<?php

use Illuminate\Database\Seeder;  
use App\Users;

class UserTableSeeder extends Seeder {

  public function run()
  {
    DB::table('users')->delete();
    $pwd = md5('123456');
    $time = time();
    for ($i=0; $i < 10; $i++) {
      Users::create([
	'name'	=> 'Name.' . $i,
	'email' => 'test' . $i . '@163.com',
	'password' => $pwd,
	'role'   => 'editor',
	'valid' => true,
	'last_login' => $time
      ]);
    }
  }

}
