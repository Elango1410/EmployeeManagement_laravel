<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $user;
    public function __construct(User $user)
    {
        //

        $this->user = $user;
        return $user;
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
        $name=$this->user->name;
        // $message=$this->user->password;
        // $password = Crypt::decrypt($message);
        return (new MailMessage)
            ->greeting('Hello, ' .$name)
            ->line('Welcome to MaccappStudio')
            ->line('Your registration has been successfully completed.')
            ->line('Your userId: '.$this->user->email)
            ->line('Your password:'.$this->user->pass);
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
