<?php

namespace Modules\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Modules\User\Entities\User;

class VerifyEmailNotification extends Notification
{
    // use Queueable;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return (new MailMessage)
            ->subject(Lang::getFromJson('Verifikasi Alamat Email'))
            ->greeting('Yth ' . $this->user->genderCall() . ' ' . $this->user->name)
            ->line(__('Terima kasih telah meluangkan waktu untuk mendaftarkan diri di Tanda Tangan Emas. Untuk menyelesaikan proses pendaftaran, kami membutuhkan konfirmasi anda melalui email. Mohon membuka tautan di bawah untuk mengkonfirmasi data anda di Tanda Tangan Emas.'))
            ->line(__('Email ini adalah email otomatis dan tidak dipantau. Tolong tidak membalas langsung email ini.'))
            ->action(
                Lang::getFromJson('Konfirmasi email saya'),
                $this->verificationUrl($notifiable)
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
