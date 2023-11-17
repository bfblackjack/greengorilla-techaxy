<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\UserInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class NewUserInvited extends Notification
{
    use Queueable;

    public User $user;
    public UserInvite $userInvite;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, UserInvite $userInvite)
    {
        $this->user = $user;
        $this->userInvite = $userInvite;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line($this->user->first . ', ')
                    ->line('You have been invited to join the '.config('app.name') . ' Portal.')
                    ->line('Setup your account using the link below. It\'ll be active for the next 24 hours.')
                    ->action('Create Account', URL::temporarySignedRoute('account-setup', $this->userInvite->expire_dt, ['token' => $this->userInvite->token]))
                    ->line('If you need a new link, \' you may need to contact an administrator.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
