<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DoctorController;



Route::get ('/', function(){
    return view('admin.dashboard');
})->name('dashboard');
//gestion de Roles
Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);
Route::resource('patients', PatientController::class);
Route::resource('doctors', DoctorController::class)
->except(['create', 'store', 'show']);