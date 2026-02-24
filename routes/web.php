<?php

use App\Models\Role;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RepairController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'home']);
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

//repaie
Route::get('/dashboard', [RepairController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/repair-create', [RepairController::class, 'repairCreateForm'])->name('repair-create-form')->middleware('auth');
Route::post('/repair-create', [RepairController::class, 'repairCreate'])->name('repair-create')->middleware('auth');

Route::get('/repair/{repair}', [RepairController::class, 'repairDetail'])->name('repair-detail')->middleware('auth');
Route::get('/repair/{repair}/{repair_action_type}', [RepairController::class, 'repairAction'])->name('repair-action')->middleware('auth');
Route::get('/repair-log/{repair}', [RepairController::class, 'log'])->name('repair-log')->middleware('auth');

//ยื่นขออุปกรณ์
Route::get('/equipment-request/{repair}', [RepairController::class, 'equipmentForm'])->name('equipment-request-form')->middleware('auth');
Route::post('/equipment-request/{repair}', [RepairController::class, 'equipmentSubmit'])->name('equipment-request-submit')->middleware('auth');
Route::get('/equipment-approve/{repair}', [RepairController::class, 'equipmentApproveForm'])->name('equipment-approve-form')->middleware('auth');
Route::post('/equipment-status-update/{equipment_request}', [RepairController::class, 'equipmentApprove'])->name('equipment-status-update')->middleware('auth');


Route::get('/repair-assign/{repair}', [RepairController::class, 'assignForm'])->name('repair-assign-form')->middleware('auth');
Route::post('/repair-assign/{repair}', [RepairController::class, 'assignSubmit'])->name('repair-assign-submit')->middleware('auth');


Route::delete('/attachment-remove/{attachment}', [RepairController::class,'attachmentRemove'])->name('attachment-remove')->middleware('auth');


Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
