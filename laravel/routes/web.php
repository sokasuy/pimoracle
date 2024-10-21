<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //DASHBOARD
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/', [HomeController::class, 'index'])->name('dashboard.home');
    Route::post('/authentication/users/get-worklist', [HomeController::class, 'getWorklist'])->name('home.getworklist');

    //PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('EnsureAuthenticationAccess')->group(function () {
        //AUTHENTICATION FORMS
        Route::get('/authentication/users', [UserController::class, 'index'])->name('auth.users')->middleware('EnsureUserHasPermission:authentication,users,read');
        Route::post('/authentication/users/get-users-list', [UserController::class, 'getUsersList'])->name('auth.getuserslist');
        Route::get('/authentication/users/add-user', [UserController::class, 'addUser'])->name('auth.adduser')->middleware('EnsureUserHasPermission:authentication,users,create');
        Route::post('/authentication/users/add-user/action', [UserController::class, 'actionRegister'])->name('auth.actionregister');
        Route::post('/authentication/users/change-password', [UserController::class, 'changeUserPassword'])->name('auth.changeuserpassword')->middleware('EnsureUserHasPermission:authentication,users,update');
        Route::post('/authentication/users/change-password/action', [UserController::class, 'actionChangeUserPassword'])->name('auth.actionchangeuserpwd');

        //ROLES
        Route::get('/authentication/roles', [RoleController::class, 'index'])->name('auth.roles')->middleware('EnsureUserHasPermission:authentication,roles,read');
        Route::post('/authentication/roles/get-roles-list', [RoleController::class, 'getRolesList'])->name('auth.getroles');
        Route::get('/authentication/roles/add-roles', [RoleController::class, 'addRoles'])->name('auth.addroles')->middleware('EnsureUserHasPermission:authentication,roles,create');
        Route::post('/authentication/roles/add-roles/action', [RoleController::class, 'actionRegister'])->name('auth.actionregisterroles');

        //PERMISSION
        Route::get('/authentication/permissions', [PermissionController::class, 'index'])->name('auth.permission')->middleware('EnsureUserHasPermission:authentication,permission,read');
        Route::post('/authentication/permissions/get-permission-list', [PermissionController::class, 'getPermissionList'])->name('auth.getpermission');
        Route::post('/authentication/permissions/change-permission', [PermissionController::class, 'changePermission'])->name('auth.changepermission')->middleware('EnsureUserHasPermission:authentication,permission,update');
        Route::post('/authentication/permissions/change-permission/action', [PermissionController::class, 'actionChangePermission'])->name('auth.actionChangePermission');
        Route::get('/authentication/permissions/add-permission', [PermissionController::class, 'addPermissions'])->name('auth.addpermissions')->middleware('EnsureUserHasPermission:authentication,permission,create');
        Route::post('/authentication/permissions/add-permission/action', [PermissionController::class, 'actionRegister'])->name('auth.actionregisterpermission');
    });
});

require __DIR__ . '/auth.php';
