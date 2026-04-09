<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserStoreRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function members()
    {
        $users = User::select('id', 'full_name')->with('unit')->where('plaza_id', request()->user()->plaza_id)
            ->where('role', 'member')
            ->get();

        return response()->json([
            'message' => 'Users Retreived Successfully',
            'users' => $users,
        ], 200);
    }

    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['role'] = 'member';
        $validated['plaza_id'] = request()->user()->plaza_id;
        $existingUserEmail = User::where('email', $validated['email'])
            ->where('plaza_id', $request->user()->plaza_id)
            ->first();
        $existingUserFullName = User::where('full_name', $validated['full_name'])
            ->where('plaza_id', $request->user()->plaza_id)
            ->first();

        if ($existingUserEmail) {
            return response()->json(['error' => 'Email already used in this plaza'], 422);
        }
        if ($existingUserFullName) {
            return response()->json(['error' => 'Full Name Must be unique '], 422);
        }

        $user = User::create($validated);

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request)
    {
        $validated = $request->validated();
        $user = User::findOrFail($validated['user_id']);

        // Check email only if it's being changed
        if (isset($validated['email']) && $validated['email'] !== $user->email) {
            $existingUser = User::where('email', $validated['email'])
                ->where('plaza_id', $request->user()->plaza_id)
                ->where('id', '!=', $validated['user_id'])
                ->first();

            if ($existingUser) {
                return response()->json(['error' => 'Email already used in this plaza'], 422);
            }
        }

        // Only hash password if provided
        // if (isset($validated['password'])) {
        //     $validated['password'] = Hash::make($validated['password']);
        // } else {
        //     unset($validated['password']);
        // }

        $user->update($validated);

        return new UserResource($user);
    }

    // public function show(Request $request, User $user): Response
    // {
    //     return new UserResource($user);
    // }

    // public function destroy(Request $request, User $user): Response
    // {
    //     $user->delete();

    //     return response()->noContent();
    // }
}
