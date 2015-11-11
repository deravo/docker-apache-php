<?php
namespace App\Services;

use App\Contracts\smsContract;

class smsService implements smsContract
{
    public function callMe($controller)
    {
        dd('Call Me From TestServiceProvider In '.$controller);
    }

    public function send($targetPhone, $message)
    {
        dd('phone num is : ' . $targetPhone);
        dd('message is : ' . $message);
    }
}