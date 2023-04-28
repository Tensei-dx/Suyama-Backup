<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = 'Tensei';
        return $this->view('emails.notificationMail')
            ->from($this->data['emailAdd'], $name)
            ->replyTo($this->data['emailAdd'], $name)
            ->subject($this->data['subject'])
            // ->cc($this->data['emailAdd'], $name)
            // ->bcc($this->data['emailAdd'], $name)
            ->with($this->data);
    }
}
