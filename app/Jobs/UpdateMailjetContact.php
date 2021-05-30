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
use Mailjet\Resources;
use Mailjet;

class UpdateMailjetContact implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $email;
    public $deleted;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $email = false, $deleted = false)
    {
        $this->user = $user;
        $this->email = $email;
        $this->deleted = $deleted;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mailjet::put(Resources::$Contact, [
                'id' => $this->deleted ? $this->email : $this->user->mailjet_id,
                'body' => [
                    'Name' => $this->deleted ? $this->email : $this->user->name,
                    'IsExcludedFromCampaigns' => $this->deleted ? true : !$this->user->notify_new_tools && !$this->user->notify_new_products,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
