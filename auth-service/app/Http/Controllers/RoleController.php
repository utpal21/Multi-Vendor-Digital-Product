<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return Role::with('permissions')->get();
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'slug' => 'required|unique:roles']);

        return Role::create($request->only(['name', 'slug']));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required', 'slug' => 'required|unique:roles,slug,' . $role->id]);

        $role->update($request->only(['name', 'slug']));
        return $role;
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['message' => 'Role deleted']);
    }
}

