<?php
namespace App\Libraries\Sms;

interface SmsContract
{

    public function callMe($controller);

    public function send($targetPhone, $message);

    public function loadConfig($conf);
}
