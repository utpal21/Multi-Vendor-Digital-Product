<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        return response()->json($request->user()->load('profile', 'roles'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $user->update($request->only(['name', 'username', 'email']));

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['address', 'phone', 'dob', 'bio'])
        );

        return response()->json(['message' => 'Profile updated', 'user' => $user->load('profile')]);
    }

    public function attachRole(Request $request, $userId)
    {
        $request->validate(['role_id' => 'required|exists:roles,id']);
        $user = User::findOrFail($userId);
        $user->roles()->attach($request->role_id);

        return response()->json(['message' => 'Role attached']);
    }

    public function detachRole(Request $request, $userId)
    {
        $request->validate(['role_id' => 'required|exists:roles,id']);
        $user = User::findOrFail($userId);
        $user->roles()->detach($request->role_id);

        return response()->json(['message' => 'Role detached']);
    }
}

