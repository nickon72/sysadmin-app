<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\PrinterController; 
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\AccessPointController;




// Главная
Route::get('/', function () {
    return view('welcome');
});

// Дашборд
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Аутентификация
require __DIR__.'/auth.php';

// Защищённые роуты
Route::middleware('auth')->group(function () {

    // Профиль
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // УЧЁТНЫЕ ДАННЫЕ
    Route::get('/credentials', [CredentialController::class, 'index'])->name('credentials.index');
    Route::post('/credentials', [CredentialController::class, 'store'])->name('credentials.store');
    Route::get('/credentials/create', [CredentialController::class, 'create'])->name('credentials.create');
    Route::get('/credentials/{id}/edit', [CredentialController::class, 'edit'])->name('credentials.edit');
    Route::put('/credentials/{id}', [CredentialController::class, 'update'])->name('credentials.update');
    Route::delete('/credentials/{id}', [CredentialController::class, 'destroy'])->name('credentials.destroy');

    // ПОКАЗАТЬ/СКРЫТЬ ПАРОЛЬ
    Route::post('/credentials/{id}/show', [CredentialController::class, 'show'])->name('credentials.show');
    Route::post('/credentials/{id}/hide', [CredentialController::class, 'hide'])->name('credentials.hide');

    // ЭКСПОРТ
    Route::get('/credentials/export-excel', [CredentialController::class, 'export'])->name('credentials.export');
    Route::get('/phones/export', [PhoneController::class, 'export'])->name('phones.export');
    Route::get('/employees/export', [EmployeeController::class, 'export'])->name('employees.export');
    Route::get('/printers/export', [PrinterController::class, 'export'])->name('printers.export');

Route::post('/computers/import', [ComputerController::class, 'import'])->name('computers.import');
Route::post('/computers/ping-all', [ComputerController::class, 'pingAll']);
// Route::post('/printers/refresh', [PrinterController::class, 'refresh']);
Route::post('/printers/refresh', [PrinterController::class, 'refreshToner'])->name('printers.refresh');


    // ДРУГИЕ РЕСУРСЫ
    Route::resource('employees', EmployeeController::class);
    Route::resource('phones', PhoneController::class);
    Route::resource('printers', PrinterController::class);
    Route::resource('computers', ComputerController::class);
    Route::resource('access-points', AccessPointController::class)->only(['index']);

});