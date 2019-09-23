<?php

namespace Modules\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\User\Entities\User;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(__('Permintaan Atur Ulang Kata Sandi'))
                    ->greeting("Yth " . $this->user->name)
                    ->line('Anda menerima email ini karena sistem kami menerima permintaan untuk mengatur ulang kata sandi akun anda.')
                    ->action('Atur ulang kata sandi saya', route('password.reset', $this->token))
                    ->line('Tautan akan kadaluarsa dalam 1 jam.')
                    ->line('Jika anda merasa tidak melakukan permintaan ini, silahkan abaikan email ini.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
