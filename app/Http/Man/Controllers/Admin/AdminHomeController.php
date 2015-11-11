<?php
namespace App\Http\Man\Controllers\Admin;

use App\Models\Man\Manager;
use App\Http\Man\Controllers\Controller;

class AdminHomeController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {
	   return "this is a group in group test";
	}

    public function showProfile($id)
    {
        return view('user.profile', [
        	'user' => Manager::find($id),
        	'site_url' => url()
        ]);
    }

    /**
     * Store a secret message for the user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function storeSecret(Request $request, $id)
    {
        $user = User::find($id);

        $user->fill([
            'secret' => Crypt::encrypt($request->secret)
        ])->save();
    }
}
