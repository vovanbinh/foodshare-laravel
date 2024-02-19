<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $email;
    protected $username;
    protected $verificationCode;
    public function __construct($email, $username, $verificationCode)
    {
        $this->email = $email;
        $this->username = $username;
        $this->verificationCode = $verificationCode;
    }
    public function handle()
    {
        $email = $this->email;
        $username = $this->username;
        $verificationCode = $this->verificationCode;
        Mail::send('view_send_email', ['username' => $username, 'verificationCode' => $verificationCode], function ($emailMessage) use ($email) {
            $emailMessage->subject('FoodShare - Verifi Code');
            $emailMessage->to($email);
        });
    }
}