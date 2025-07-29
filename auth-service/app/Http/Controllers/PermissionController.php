<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return Permission::all();
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'slug' => 'required|unique:permissions']);
        return Permission::create($request->only(['name', 'slug']));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate(['name' => 'required', 'slug' => 'required|unique:permissions,slug,' . $permission->id]);

        $permission->update($request->only(['name', 'slug']));
        return $permission;
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(['message' => 'Permission deleted']);
    }
}
