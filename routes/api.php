<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ActorController as ApiActorController;

Route::get('/actors/prompt-validation', [ApiActorController::class, 'promptValidation']);
