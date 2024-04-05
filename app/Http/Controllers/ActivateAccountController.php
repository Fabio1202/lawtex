<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ActivateAccountController extends Controller
{
    public function index()
    {
        return view('auth.activate-account', ['email' => request()->email]);
    }

    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = new User();

        $user->email = $request->query('email');
        $user->name = $validated['name'];
        $user->password = Hash::make($validated['password']);

        $user->save();

        return redirect(route('login'));
    }
}
