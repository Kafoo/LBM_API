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

function setInfos($request)
{    
    $infos = [];
    $infos['name'] = isset($request->name) ? $request->name : '<i>[ non-précisé ]</i>';
    $infos['email'] = isset($request->email) ? $request->email : '<i>[ non-précisé ]</i>';
    $infos['phone'] = isset($request->phone) ? $request->phone : '<i>[ non-précisé ]</i>';
    $infos['eventtype'] = isset($request->eventtype) ? $request->eventtype : '<i>[ non-précisé ]</i>';
    $infos['guests'] = isset($request->guests) ? $request->guests : '<i>[ non-précisé ]</i>';
    $infos['eventdate'] = isset($request->eventdate) ? $request->eventdate : '<i>[ non-précisé ]</i>';
    $infos['message'] = isset($request->message) ? nl2br($request->message) : '<i>[ non-précisé ]</i>';
    $infos['date'] = date("d-m-Y");
    $infos['time'] = date("h:i", time()+3600);
    return $infos;
}

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/hello', function () {
  return "Hello World!";
});

Route::post('/sendcontact', function (Request $request) {

    $file = 'log.txt';
    $newLog = "\n\n--- ";
    $newLog .= date("Y-m-d h:i:sa");
    $newLog .= " ---\n";
    $newLog .= "IP : " . $request->ip() . "\n";
    $newLog .= "User Agent : " . $request->userAgent() . "\n";
    $newLog .= "Inputs : " . json_encode($request->all());
    file_put_contents($file, $newLog, FILE_APPEND | LOCK_EX);

    $infos = setInfos($request);
    $email = new EmailController;

    if ($request->status == 'error') {
        $email->sendErrorEmail($infos);
    } else {
        $email->sendContactEmail($infos);
    }
});

Route::get('/testlogit', function () {
    $file = 'log.txt';
    $newLog = "\n\n--- ";
    $newLog .= date("Y-m-d h:i:sa");
    $newLog .= " ---\n";
    $newLog .= "Log testing";
    file_put_contents($file, $newLog, FILE_APPEND | LOCK_EX);
    
    $infos = setInfos(null);
    $email = new EmailController;
    $email->sendContactEmail($infos);
});