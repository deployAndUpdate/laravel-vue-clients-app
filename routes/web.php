<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ClientController;
Route::get('/', [ClientController::class, 'index'])->name('clients.index');
Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');

// Форма создания
Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');

// Форма редактирования

// Сохранение нового клиента
Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');

// Форма редактирования (предположим, что это форма для отображения данных клиента)
Route::get('/clients/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit');

// Обновление существующего клиента
Route::put('/clients/{id}', [ClientController::class, 'update'])->name('clients.update'); // Используем update вместо edit

// Удаление клиента
Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
