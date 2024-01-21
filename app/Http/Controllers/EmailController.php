<?php

namespace App\Http\Controllers;

use App\Mail\GlobalMail;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendLogEmail($newLog)
    {
        $subject = 'LBM - Nouveau contact';
        $body = $newLog;

        Mail::to('ant.guillard@gmail.com')->send(new GlobalMail($subject, $body));

        return "Email sent successfully!";
    }
}
