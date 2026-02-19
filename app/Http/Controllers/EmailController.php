<?php

namespace App\Http\Controllers;

use App\Mail\GenericNotificationMail;
use App\Mail\LbmContactMail;
use App\Mail\MarcoContactMail;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendContactEmail($contactInfos)
    {
        $subject = 'Nouvelle demande de contact';
        $infos = $contactInfos;

        Mail::to('ant.guillard@gmail.com')->send(new LbmContactMail($subject, $infos));
        Mail::to('nadia@lesbonnesmanieres.paris')->send(new LbmContactMail($subject, $infos));

        return "Email sent successfully!";
    }

    public function sendMarcoRandosEmail($contactInfos)
    {
        $subject = 'Nouvelle demande de contact';
        $infos = $contactInfos;

        Mail::to('ant.guillard@gmail.com')->send(new MarcoContactMail($subject, $infos));

        if (!str_contains($infos['message'] ?? '', 'kafootest')) {
            Mail::to('contact@marcorandos.com')->send(new MarcoContactMail($subject, $infos));
        }

        return "Email sent successfully!";
    }

    public function sendTestEmail($contactInfos)
    {
        $subject = 'Nouvelle demande de contact';
        $infos = $contactInfos;

        Mail::to('ant.guillard@gmail.com')->send(new LbmContactMail($subject, $infos));

        return "Email sent successfully!";
    }

    public function sendErrorEmail($contactInfos)
    {
        $subject = 'Nouvelle demande de contact (error)';
        $infos = $contactInfos;

        Mail::to('ant.guillard@gmail.com')->send(new LbmContactMail($subject, $infos));

        return "Email sent successfully!";
    }

    public function sendNotification(array $recipients, string $subject, string $projectName, array $fields)
    {
        $infos = [
            'projectName' => $projectName,
            'fields' => $fields,
            'date' => date("d-m-Y"),
            'time' => date("H:i", time() + 3600),
        ];

        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send(new GenericNotificationMail($subject, $infos));
        }

        return "Email sent successfully!";
    }
}
