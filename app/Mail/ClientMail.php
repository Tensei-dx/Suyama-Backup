<?php
// <System Name> iBMS
// <Program Name> ClientMail.php
// <Create> 2020.03.17 TP Harvey
// <Update>

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientMail extends Mailable
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
        return $this->view('emails.clientMail')
            ->from($this->data['emailAdd'], $name)
            ->replyTo($this->data['emailAdd'], $name)
            ->subject($this->data['subject'])
            ->with($this->data);
    }
}
