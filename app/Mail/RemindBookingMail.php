<?php

namespace App\Mail;

use App\Models\BookPMS;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemindBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The BookPMS instance.
     *
     * @var \App\BookPMS
     */
    protected $ibmsBooking;

    /**
     * The raw reservaiton detail from the PMS.
     *
     * @var array
     */
    protected $pmsBooking;

    /**
     * Create a new message instance.
     * @param  \App\BookPMS  $ibmsBooking
     * @param  array  $pmsBooking
     * @return void
     */
    public function __construct(BookPMS $ibmsBooking, array $pmsBooking)
    {
        $this->ibmsBooking = $ibmsBooking;
        $this->pmsBooking = $pmsBooking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.bookings.remind')
            ->from(config('app.client.email'), config('app.client.name'))
            ->subject(
                "【ようこそ" . config('app.client.name') . "へ！】ご予約のリマインドメールです
                【Welcome to " . config('app.client.name') . "!】A reminder for your reservation."
            )
            ->with([
                'ibmsBooking' => $this->ibmsBooking,
                'pmsBooking' => $this->pmsBooking
            ]);
    }
}
