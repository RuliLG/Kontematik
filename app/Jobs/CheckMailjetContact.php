<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Mailjet;

class CheckMailjetContact implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $contact = Mailjet::getSingleContact($this->user->email);
            $body = $contact->getBody();
            Log::debug('Datos del contacto', [
                'contact' => $body,
            ]);
        } catch (\Exception $e) {
            dispatch(new AddContactToMailjet($this->user));
            return;
        }

        if (isset($body) && $body['Count'] === 1) {
            $data = $body['Data'][0];
            $this->user->mailjet_id = $data['ID'];
            $this->user->save();
            dispatch(new UpdateMailjetContact($this->user));
        }
    }
}
