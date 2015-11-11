<?php
namespace App\Contracts;

interface smsContract
{
    public function callMe($controller);

    public function send($targetPhone, $message);
}