<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ErrorNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;
    public $trace;
    public $additional;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(\Exception $e, $additionalInfo = [])
    {
        $this->message = $e->getMessage();
        $this->trace = $e->getTraceAsString();
        $this->additional = $additionalInfo;
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
        $message = (new SlackMessage)
                    ->from('Kontematik')
                    ->content(':rotating_light::rotating_light: An error happened :rotating_light::rotating_light:')
                    ->attachment(function ($attachment) {
                        $attachment->title('Error information')
                            ->fields([
                                'message' => $this->message,
                                'trace' => $this->trace,
                            ]);
                    });

        if (!empty($this->additional)) {
            $message->attachment(function ($attachment) {
                $attachment->title('Additional information')
                    ->fields($this->additional);
            });
        }

        return $message;
    }
}
