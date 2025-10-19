<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Chat AI Routes (No CSRF required)
Route::post('/chat', [ChatController::class, 'chat'])->name('api.chat');
Route::get('/chat/test', [ChatController::class, 'test'])->name('api.chat.test');
