<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        // メール認証リンクの生成（親のメソッドは protected なので自前で再構築）
        $verificationUrl = $this->verificationUrl($notifiable);

       return (new MailMessage)
        ->subject('メール確認のお願い')
        ->view('emails.verify-email', [
            'url' => $verificationUrl,
            'user' => $notifiable,
        ]);

    }

    /**
     * 認証用URLを生成
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
