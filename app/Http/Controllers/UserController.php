<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
            });
        }

        $users = $query->latest()->paginate(15);

        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'role'     => 'required|in:admin,chairman,assistant,member',
                'password' => ['required', 'confirmed', Password::min(8)],
            ],
            [
                'name.required'     => 'Please enter the user\'s full name.',
                'email.required'    => 'An email address is required.',
                'email.email'       => 'Please enter a valid email address.',
                'email.unique'      => 'This email is already registered.',
                'role.required'     => 'Please select a role for the user.',
                'role.in'           => 'The selected role is not valid.',
                'password.required' => 'A password is required.',
                'password.confirmed'=> 'The password confirmation does not match.',
                'password.min'      => 'Password must be at least 8 characters.',
            ]
        );

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate(
            [
                'name'     => 'required|string|max:255',
                'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
                'role'     => 'required|in:admin,chairman,assistant,member',
                'password' => ['nullable', 'confirmed', Password::min(8)],
            ],
            [
                'name.required'     => 'Please enter the user\'s full name.',
                'email.required'    => 'An email address is required.',
                'email.email'       => 'Please enter a valid email address.',
                'email.unique'      => 'This email is already taken by another user.',
                'role.required'     => 'Please select a role for the user.',
                'role.in'           => 'The selected role is not valid.',
                'password.confirmed'=> 'The password confirmation does not match.',
                'password.min'      => 'Password must be at least 8 characters.',
            ]
        );

        // Only update password if a new one was provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.show', $user->id)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}