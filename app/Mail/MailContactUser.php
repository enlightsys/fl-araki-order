<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailContactUser extends Mailable
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
        return $this->subject('【荒木生花店】お問い合わせを送信しました。')
            ->text('email.contact_user')
            ->with('vars', $this->vars);
    }
}
