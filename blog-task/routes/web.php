<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/home', [TaskController::class, 'index'])->name('tasks.index'); // Ruta para obtener la lista de tareas
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store'); // Crear una tarea
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update'); // Actualizar una tarea
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy'); // Eliminar una tarea
Route::put('/tasks/{id}/complete', [TaskController::class, 'complete'])->name('tasks.complete'); // Completar una tarea
Route::get('/csrf-token', [TaskController::class, 'getToken']); // Obtener el token CSRF
