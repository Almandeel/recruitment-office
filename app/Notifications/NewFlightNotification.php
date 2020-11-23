<?php

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\ExternalOffice\Models\Flight;

class NewFlightNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
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
            'title' => 'رسالة جديدة',
            'body' => 'رسالة جديدة',
            'created' => Carbon::now()->toIso8601String()
        ];
    }
}
