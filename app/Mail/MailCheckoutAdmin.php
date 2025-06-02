<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailCheckoutAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($vars, $products)
    {
        $this->vars = json_decode(json_encode($vars));
        $this->products = json_decode(json_encode($products));
    }

    public function build()
    {
        $obj = $this->subject('【荒木生花店】ご注文ありがとうございます')
            ->text('email.checkout_admin')
            ->with(['vars' => $this->vars, 'products' => $this->products]);

        return $obj;
    }
}
