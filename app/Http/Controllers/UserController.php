<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Password;

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

        if ($user->isAdmin() && User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->count() === 1 && !$validated['admin'])
        {
            return back()->withErrors(['admin' => 'You cannot remove the admin role from the only admin user']);
        }

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
        // Check if the user is the only admin
        if ($user->isAdmin() && User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->count() === 1)
        {
            return back()->withErrors(['admin' => 'You cannot delete the only admin user']);
        }

        $user->delete();

        return redirect(route('users.index'));
    }

    public function resetPassword(User $user)
    {
        Password::sendResetLink(
            $user->only('email')
        );
        return back();
    }
}
