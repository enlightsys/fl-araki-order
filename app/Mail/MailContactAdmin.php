<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailContactAdmin extends Mailable
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
        return $this->subject('【荒木生花店】お問い合わせがありました。')
            ->text('email.contact_admin')
            ->with('vars', $this->vars);
    }
}
