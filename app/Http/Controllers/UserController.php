<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::where('role' ,'!=' ,'admin')->paginate(20);
        return view();
    }
}
