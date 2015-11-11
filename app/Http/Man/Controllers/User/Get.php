<?php
namespace App\Http\Controllers\User;

use App\Models\User;
use App\Http\Controllers\Controller;

class Get extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function showProfile($id)
    {
        return view('user.profile', [
        	'user' => User::find($id),
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
