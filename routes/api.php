<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatGPTController;

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

Route::group(['prefix' => 'chat_gpt'], function () {
    Route::post('test_chat_gpt', [ChatGPTController::class,'testChatGPT']);
    Route::post('message', [ChatGPTController::class,'message']);
    Route::get('message', [ChatGPTController::class,'verifyWebhook']);
});

