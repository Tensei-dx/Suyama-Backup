<?php

namespace App\Mail;

use App\Models\BookPMS;
use App\Models\ParamSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The data needed in the content.
     *
     * @var mixed
     */
    public $data;

    /**
     * The ParamSetting instance.
     * @var \App\ParamSettings
     */
    protected $param;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $data
     * @return void
     */
    public function __construct(BookPMS $ibmsBooking)
    {
        $this->ibmsBooking = $ibmsBooking;
        $this->param = ParamSettings::first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.thank_you')
            ->from(config('app.client.email'), config('app.client.name'))
            ->subject(
                "【" . config('app.client.name') . "】 ご利用ありがとうございました
            【" . config('app.client.name') . "】Thank you for your stay with us"
            )
            ->with([
                'ibmsBooking' => $this->ibmsBooking,
                'param' => $this->param
            ]);
    }
}
