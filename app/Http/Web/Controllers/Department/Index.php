<?php namespace App\Http\Controllers\Department;

use App\Models\User;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function index()
    {
        return view('department.index');
    }
}
