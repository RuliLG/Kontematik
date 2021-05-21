<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class WarningNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $exception;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(\Exception $e)
    {
        $this->exception = $e;
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
                    ->content(':warning: Warning :warning:')
                    ->attachment(function ($attachment) {
                        $attachment->title('Warning information')
                            ->fields([
                                'message' => $this->exception->getMessage(),
                                'trace' => $this->exception->getTraceAsString(),
                            ]);
                    });
    }
}
