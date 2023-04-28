<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancellationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

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

        return $this->view('emails.cancellationEmail')
            ->from($this->data['emailAdd'], $name)
            ->replyTo($this->data['emailAdd'], $name)
            ->subject($this->data['subject'])
            ->with(
                [
                    'guest_name' => $this->data['guest_name'],
                    'checkin' => $this->data['checkin'],
                    'checkout' => $this->data['checkout'],
                    'room' => $this->data['room']
                ]
            );
    }
}
