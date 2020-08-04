<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans('notifications.auth.reset_password.subject'))
            ->line(trans('notifications.auth.reset_password.header'))
            ->action(trans('notifications.auth.reset_password.action'), url(config('app.url') . route('auth.password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
            ->line(trans('notifications.auth.reset_password.body', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(trans('notifications.auth.reset_password.footer'));
    }

    /**
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
