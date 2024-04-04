<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {

        return view('users.index', [
            'users' => User::paginate(10)
        ]);
    }
}
