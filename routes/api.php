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

if (!function_exists('setInfos')) {
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

Route::post('marcorandos/sendcontact', function (Request $request) {

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
        $email->sendMarcoRandosEmail($infos);
    }
});

Route::get('/test/sendcontact', function () {

    $file = 'log.txt';
    $newLog = "\n\n--- ";
    $newLog .= date("Y-m-d h:i:sa");
    $newLog .= " ---\n";
    $newLog .= "Log testing";
    file_put_contents($file, $newLog, FILE_APPEND | LOCK_EX);

    $infos = setInfos(null);
    $email = new EmailController;

    if ($request->status == 'error') {
        $email->sendErrorEmail($infos);
    } else {
        $email->sendTestEmail($infos);
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
    $email->sendTestEmail($infos);
});

/**
 * Creates a generic notification endpoint handler.
 *
 * @param string $projectName Name of the project (displayed in email header)
 * @param array $recipients Array of email addresses to send notifications to
 * @param string $subject Email subject line
 * @return \Closure Route handler function
 */
if (!function_exists('createNotificationEndpoint')) {
    function createNotificationEndpoint(string $projectName, array $recipients, string $subject = 'Nouvelle notification')
    {
        return function (Request $request) use ($projectName, $recipients, $subject) {
            // Log the request
            $file = 'log.txt';
            $newLog = "\n\n--- ";
            $newLog .= date("Y-m-d h:i:sa");
            $newLog .= " ---\n";
            $newLog .= "Project: " . $projectName . "\n";
            $newLog .= "IP : " . $request->ip() . "\n";
            $newLog .= "User Agent : " . $request->userAgent() . "\n";
            $newLog .= "Inputs : " . json_encode($request->all());
            file_put_contents($file, $newLog, FILE_APPEND | LOCK_EX);

            // Prepare fields from request, converting empty values
            $fields = [];
            foreach ($request->all() as $key => $value) {
                if (is_string($value) && trim($value) !== '') {
                    $fields[$key] = nl2br(e($value));
                } else if (is_array($value)) {
                    $fields[$key] = e(json_encode($value));
                } else if ($value !== null && $value !== '') {
                    $fields[$key] = e($value);
                } else {
                    $fields[$key] = '<i>[ non-précisé ]</i>';
                }
            }

            // Send the email
            $email = new EmailController;
            $email->sendNotification($recipients, $subject, $projectName, $fields);

            return response()->json(['status' => 'success', 'message' => 'Notification envoyée']);
        };
    }
}

// Test endpoint for generic notifications
Route::post('/test/genericnotify', createNotificationEndpoint(
    'Test Project',
    ['ant.guillard@gmail.com'],
    'Test notification générique'
));

// Kafoo Guestbook - kafoo.dev
Route::post('/kafoo/guestbook', createNotificationEndpoint(
    'Kafoo Guestbook',
    ['ant.guillard@gmail.com'],
    'Nouvelle signature dans le livre d\'or'
));

// Aimer - Nouveau foyer
Route::post('/aimer/newfoyer', createNotificationEndpoint(
    'Aimer',
    ['ant.guillard@gmail.com'],
    'Nouveau foyer enregistré sur Aimer'
));

// Aimer - Demande de contact
Route::post('/aimer/sendcontact', createNotificationEndpoint(
    'Aimer',
    ['ant.guillard@gmail.com'],
    'Nouvelle demande sur Aimer'
));