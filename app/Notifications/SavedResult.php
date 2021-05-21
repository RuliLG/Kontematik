<?php

namespace App\Notifications;

use App\Models\Service;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SavedResult extends Notification implements ShouldQueue
{
    use Queueable;

    public $output;
    public $user;
    public $service;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($output, User $user, Service $service)
    {
        $this->output = $output;
        $this->user = $user;
        $this->service = $service;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
                    ->from('Kontematik')
                    ->content(':star2: User #' . $this->user->id . ' just saved a result from *' . $this->service->name . '*')
                    ->attachment(function ($attachment) {
                        $attachment->title('Output')
                            ->content($this->output);
                    });
    }
}
