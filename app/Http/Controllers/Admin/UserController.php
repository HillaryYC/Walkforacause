<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in([
                User::ROLE_USER,
                User::ROLE_ADMIN,
                User::ROLE_SUPER_ADMIN,
            ])],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('status', "Role updated for {$user->name}.");
    }
}
