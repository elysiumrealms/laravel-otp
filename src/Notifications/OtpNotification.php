<?php

namespace Elysiumrealms\Otp\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Intervention\Image\Facades\Image;

class OtpNotification extends Notification
{
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Get the notification's delivery channels.
     *
     * @param \Elysiumrealms\Otp\Models\Otp $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            \Illuminate\Support\Arr::get(
                config('otp.providers'),
                $notifiable->via
            )
        ];
    }

    /**
     * Build the mail message.
     *
     * @param \Elysiumrealms\Otp\Models\Otp $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get(
                'otp::notifications.mail.subject',
                [
                    'otp' => $notifiable->token,
                ],
                $this->locale
            ))
            ->view('otp::mail.verification', [
                'title' => Lang::get(
                    'otp::notifications.mail.title',
                    [],
                    $this->locale
                ),
                'subtitle' => Lang::get(
                    'otp::notifications.mail.subtitle',
                    [],
                    $this->locale
                ),
                'paragraph' => Lang::get(
                    'otp::notifications.mail.paragraph',
                    [
                        'validity' => $notifiable->validity,
                    ],
                    $this->locale
                ),
                'otp' => $notifiable->token,
                'logo' => empty($logo = config('otp.mail.logo'))
                    ? null : asset($logo),
                'banner' => asset('vendor/laravel-otp/images/banner.png'),
            ]);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param \Elysiumrealms\Otp\Models\Otp $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return $this->buildMailMessage($notifiable);
    }

    /**
     * Get the twillio representation of the notification.
     *
     * @param \Elysiumrealms\Otp\Models\Otp $notifiable
     * @return \Illuminate\Notifications\Messages\TwilioSmsMessage
     */
    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())
            ->content(Lang::get(
                'otp::notifications.sms.content',
                [
                    'otp' => $notifiable->token,
                    'name' => config('app.name'),
                    'validity' => $notifiable->validity,
                ],
                $this->locale
            ));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param \Elysiumrealms\Otp\Models\Otp $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'otp' => $notifiable->token,
            'validity' => $notifiable->validity,
        ];
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
