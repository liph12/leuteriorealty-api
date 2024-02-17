<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Verification extends Mailable
{
    use Queueable, SerializesModels;
    public $email;
    public $verification;
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct($email, $verification, $name)
    {
        $this->email = $email;
        $this->verification = $verification;
        $this->name = $name;
    }

    /**
     * Build the message. 
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email)->from('fh.filipinohomes@gmail.com', 'LR Support Team')->subject('LR Verification Code')->markdown('emails.verification');
    }
}
