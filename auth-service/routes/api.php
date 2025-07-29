<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

// Public routes (no auth)
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// Protected routes (auth:sanctum)
Route::middleware(['auth:sanctum'])->group(function () {

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout']);

    // Users CRUD - Only admin or authorized roles can manage users
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });

    // Roles CRUD - only admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::get('/roles/{id}', [RoleController::class, 'show']);
        Route::put('/roles/{id}', [RoleController::class, 'update']);
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

        // Attach / detach permissions to roles
        Route::post('/roles/{id}/permissions/attach', [RoleController::class, 'attachPermissions']);
        Route::post('/roles/{id}/permissions/detach', [RoleController::class, 'detachPermissions']);
    });

    // Permissions CRUD - only admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index']);
        Route::post('/permissions', [PermissionController::class, 'store']);
        Route::get('/permissions/{id}', [PermissionController::class, 'show']);
        Route::put('/permissions/{id}', [PermissionController::class, 'update']);
        Route::delete('/permissions/{id}', [PermissionController::class, 'destroy']);
    });

    // Current authenticated user details (optional route)
    Route::get('/me', function (Request $request) {
        return response()->json($request->user()->load('roles.permissions'));
    });
});