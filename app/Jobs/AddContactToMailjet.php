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

class AddContactToMailjet implements ShouldQueue
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
            $response = Mailjet::createContact([
                'Name' => $this->user->name,
                'Email' => $this->user->email,
                'IsExcludedFromCampaigns' => !$this->user->notify_new_tools && !$this->user->notify_new_products,
            ]);
            $response = $response->getData();
            if (isset($response[0])) {
                $response = $response[0];
            }

            $listResponse = Mailjet::createListRecipient([
                'ContactID' => $response['ID'],
                'ListID' => config('services.mailjet.list_id'),
            ]);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
