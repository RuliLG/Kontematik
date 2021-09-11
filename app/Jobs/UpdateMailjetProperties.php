<?php

namespace App\Jobs;

use App\Models\Niche;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mailjet\LaravelMailjet\Facades\Mailjet;
use Mailjet\Resources;

class UpdateMailjetProperties implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = Mailjet::getClient();
        // Mailjet needs to have a property for each niche, as well as a property for the language code
        $niches = Niche::whereNotNull('code')->get();
        foreach ($niches as $niche) {
            $body = [
                'Datatype' => 'bool',
                'Name' => 'niche_' . $niche->code,
                'NameSpace' => 'static'
            ];
            try {
                $response = $client->post(Resources::$Contactmetadata, ['body' => $body]);
                if ($response->success()) {
                    logger('Added ' . $niche->code . ' to Mailjet');
                }
            } catch (\Exception $e) {
                logger('Error adding ' . $niche->code . ' to Mailjet: ' . $e->getMessage());
            }
        }

        $body = [
            'Datatype' => 'str',
            'Name' => 'preferred_language',
            'NameSpace' => 'static'
        ];
        try {
            $response = $client->post(Resources::$Contactmetadata, ['body' => $body]);
            if ($response->success()) {
                logger('Added preferred_language to Mailjet');
            }
        } catch (\Exception $e) {
            logger('Error adding preferred_language to Mailjet: ' . $e->getMessage());
        }
    }
}
