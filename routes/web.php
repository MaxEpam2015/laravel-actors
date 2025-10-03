<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActorController;

Route::get('/', [ActorController::class, 'create'])->name('actors.create');
Route::post('/submit', [ActorController::class, 'store'])->name('actors.store');
Route::get('/submissions', [ActorController::class, 'index'])->name('actors.index');
