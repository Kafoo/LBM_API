<?php

use App\Http\Controllers\EmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/hello', function () {
  return "Hello Worldi!";
});

Route::post('/logit', function (Request $request) {

    $file = 'log.txt';
    $newLog = "\n\n--- ";
    $newLog .= date("Y-m-d h:i:sa");
    $newLog .= " ---\n";
    $newLog .= "IP : " . $request->ip() . "\n";
    $newLog .= "User Agent : " . $request->userAgent() . "\n";
    $newLog .= "Inputs : " . json_encode($request->all());
    file_put_contents($file, $newLog, FILE_APPEND | LOCK_EX);
    $email = new EmailController;
    $email->sendLogEmail($newLog);
});

Route::get('/testlogit', function () {
    $file = 'log.txt';
    $newLog = "\n\n--- ";
    $newLog .= date("Y-m-d h:i:sa");
    $newLog .= " ---\n";
    $newLog .= "Log testing";
    file_put_contents($file, $newLog, FILE_APPEND | LOCK_EX);
    $email = new EmailController;
    $email->sendLogEmail($newLog);
});