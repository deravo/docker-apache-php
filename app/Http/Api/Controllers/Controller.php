<?php

namespace App\Http\Api\Controllers;

use Illuminate\Validation\Validator ;
use Laravel\Lumen\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
/*
    protected function formatValidationErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }
*/
}
