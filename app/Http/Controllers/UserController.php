<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {

        return view('users.index', [
            'users' => User::paginate(10),
        ]);
    }

    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user,
        ]);
    }

    public function update(User $user)
    {
        $validated = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $validated['admin'] = request()->has('admin');

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($validated['admin']) {
            $user->assignRole('admin');
        } else {
            $user->removeRole('admin');
        }

        $user->save();

        return back();
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect(route('users.index'));
    }
}
