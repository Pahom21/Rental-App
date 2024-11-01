<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LockScreenController;
use App\Http\Controllers\LandlordController;
use App\Http\Controllers\TenantController;
use App\Http\Middleware\LockScreenMiddleware;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TenantsController;
use App\Http\Controllers\MessageTenantController;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth', LockScreenMiddleware::class])->group(function () {
    Route::prefix('landlord')->group(function () {

        Route::get('/dashboard', [LandlordController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/payments/dashboard', [LandlordController::class, 'payments'])->name('admin.payments');

        // Landlord property routes
        Route::get('/properties', [PropertyController::class, 'index'])->name('landlord.properties.index');
        Route::get('/properties/create', [PropertyController::class, 'create'])->name('landlord.properties.create');
        Route::post('/properties', [PropertyController::class, 'store'])->name('landlord.properties.store');
        Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('landlord.properties.edit');
        Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('landlord.properties.update');
        Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('landlord.properties.destroy');

        // Landlord room routes
        Route::resource('rooms', RoomController::class)->names([
            'index' => 'landlord.rooms.index',
            'create' => 'landlord.rooms.create',
            'store' => 'landlord.rooms.store',
            'edit' => 'landlord.rooms.edit',
            'update' => 'landlord.rooms.update',
            'destroy' => 'landlord.rooms.destroy',
        ]);


        // Landlord tenant routes
        Route::resource('tenants', TenantController::class)->names([
            'index' => 'landlord.tenants.index',
            'create' => 'landlord.tenants.create',
            'store' => 'landlord.tenants.store',
            'edit' => 'landlord.tenants.edit',
            'update' => 'landlord.tenants.update',
            'destroy' => 'landlord.tenants.destroy',
        ]);
        Route::put('/tenants/{tenant}/checkout', [TenantController::class, 'checkout'])->name('landlord.tenants.checkout');


        // Landlord role routes
        Route::get('roles', [RoleController::class, 'index'])->name('landlord.roles.index');
        Route::get('roles/create', [RoleController::class, 'create'])->name('landlord.roles.create');
        Route::post('roles', [RoleController::class, 'store'])->name('landlord.roles.store');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('landlord.roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('landlord.roles.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('landlord.roles.destroy');


        // Landlord user routes
        Route::resource('users', UserController::class)->names([
            'index' => 'landlord.users.index',
            'create' => 'landlord.users.create',
            'store' => 'landlord.users.store',
            'edit' => 'landlord.users.edit',
            'update' => 'landlord.users.update',
            'destroy' => 'landlord.users.destroy',
        ]);

        // Landlord messages routes
        Route::get('messages', [MessageTenantController::class, 'index'])->name('landlord.messages.index');
        Route::get('messages/create', [MessageTenantController::class, 'create'])->name('landlord.messages.create');
        Route::post('messages/send', [MessageTenantController::class, 'sendMessage'])->name('landlord.send.message');
    });

    Route::prefix('tenant')->group(function () {
        Route::get('/dashboard', [TenantsController::class, 'dashboard'])->name('tenant.dashboard');
        Route::get('/property', [TenantsController::class, 'property'])->name('tenant.property');
        Route::get('/payments', [TenantsController::class, 'payments'])->name('tenant.payments');
        Route::post('/payments/response', [TenantsController::class, 'callback'])->withoutMiddleware('auth')->name('mpesa.callback');
        Route::post('/payment', [TenantsController::class, 'storePayment'])->name('tenant.payments.store');
        Route::get('/maintenance', [TenantsController::class, 'maintenance'])->name('tenant.maintenance');
        Route::get('/messages', [TenantsController::class, 'messages'])->name('tenant.messages');
        Route::post('/tenant/maintenance/submit', [TenantsController::class, 'submitMaintenanceRequest'])->name('tenants.maintenance.store');
    });
});

// In web.php
Route::get('/test-storage', function () {
    Storage::disk('local')->put('test.txt', 'This is a test.');
    return response()->json(['success' => true]);
});


//Lockscreen routes

Route::middleware(['auth'])->group(function () {
    Route::get('/lock-screen', [LockScreenController::class, 'lockscreen'])->name('lock-screen');
    Route::post('/manual-lock', [LockScreenController::class, 'manualLock'])->name('manual-lock');
    Route::post('/unlock', [LockScreenController::class, 'unlock'])->name('unlock');
});

Route::post('/auto_screen', [LockScreenController::class, 'autoLock'])->name('auto-lock');
Route::get('/is-locked', [LockScreenController::class, 'isLocked'])->name('is-locked');
