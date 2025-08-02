<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;

// Route ke halaman utama
Route::get('/', function () {
    return redirect('/tasks');
});

// Auth routes (login, register, dll)
Auth::routes();

// Group yang membutuhkan login
Route::middleware(['auth'])->group(function () {
    // Halaman daftar tugas
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

    // Form tambah tugas
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');

    // Simpan tugas baru
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // Edit tugas
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');

    // Update tugas
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

    // Hapus tugas
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    // check box jika sudah selesai tugas nya
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');
    Route::patch('/tasks/{task}/toggle-status', [TaskController::class, 'toggleStatus'])->name('tasks.toggleStatus');
});

Auth::routes();

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
