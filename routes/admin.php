<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\BackupController;

Route::get ('/',[DashboardController::class, 'index'])->name('dashboard');
//gestion de Roles
Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);
Route::resource('patients', PatientController::class)
->only(['index', 'edit', 'update']);
Route::resource('doctors', DoctorController::class)
->only(['index', 'edit', 'update']);
Route::get('doctors/{doctor}/schedules',[DoctorController::class, 'schedules'])
->name('doctors.schedules');

Route::get('appointments/{appointment}/consultation', [AppointmentController::class, 'consultation'])
->name ('appointments.consultation');
Route::resource('appointments', AppointmentController::class);

Route::get('calendar', [CalendarController::class, 'index'])
->name('calendar.index');

// RUTAS EXISTENTES (NO MODIFICAR)
Route::resource('reports', ReportController::class);
Route::get('reports/export/pdf', [ReportController::class, 'exportToPdf'])
       ->name('reports.export.pdf');

// ========== RUTAS NUEVAS PARA PDF ==========
// Para VER PDF en navegador
Route::get('reports/export/pdf-view', [ReportController::class, 'viewPdf'])
       ->name('reports.export.pdf-view');

// Para DESCARGAR PDF
Route::get('reports/export/pdf-download', [ReportController::class, 'downloadPdf'])
       ->name('reports.export.pdf-download');
       
// Listar backups (NOTA: NO usan prefijo 'admin.', usan nombres simples)
Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
Route::post('backups/create', [BackupController::class, 'create'])->name('backups.create');
Route::post('backups/run', [BackupController::class, 'run'])->name('backups.run');
Route::post('backups/clean', [BackupController::class, 'clean'])->name('backups.clean');
Route::post('backups/{id}/restore', [BackupController::class, 'restore'])->name('backups.restore');
Route::get('backups/restore-log', [BackupController::class, 'restoreLog'])->name('backups.restore-log');
Route::get('backups/{filename}/download', [BackupController::class, 'download'])->name('backups.download');
Route::delete('backups/{id}', [BackupController::class, 'destroy'])->name('backups.destroy');