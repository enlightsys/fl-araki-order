<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailRegist extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($vars)
    {
        $this->vars = $vars;
    }

    public function build()
    {
        return $this->subject('【荒木生花店】会員登録が完了しました。')
            ->text('email.regist')
            ->with('vars', $this->vars);
    }
}
