<?php

namespace App\Notifications;

use App\Models\Result;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class TextGenerated extends Notification implements ShouldQueue
{
    use Queueable;

    public $result;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
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
     * Get the slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
                    ->from('Kontematik')
                    ->content(':eyes: The user ' . $this->result->user->email . ' just used *' . $this->result->service->name . '*')
                    ->attachment(function ($attachment) {
                        $attachment->title('Prompt')
                            ->content($this->result->prompt);
                    })
                    ->attachment(function ($attachment) {
                        $fields = [];
                        foreach ($this->result->response as $i => $result) {
                            $fields['Result #' . $i] = $result;
                        }

                        $attachment->title('Results')
                            ->fields($fields);
                    });
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
            //
        ];
    }
}
