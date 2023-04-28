<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class AdminUserCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The User instance.
     *
     * @var \App\User
     */
    public $user;

    /**
     * The Remote Lock Access User.
     *
     * @var \Illuminate\Support\Collection
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Collection $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.created')
            ->subject("管理者アカウントが作成されました/Admin Account Created");
    }
}
