<?php

namespace App\Http\Controllers;

use App\Mail\LbmContactMail;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendContactEmail($contactInfos)
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
}
