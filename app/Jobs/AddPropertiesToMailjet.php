<?php

namespace App\Jobs;

use App\Models\Niche;
use App\Models\NicheUser;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mailjet\LaravelMailjet\Facades\Mailjet;
use Mailjet\Resources;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

class AddPropertiesToMailjet implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 3600;

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
        assertNotNull($this->user->mailjet_id);
        $client = Mailjet::getClient();
        $body = [
            'Data' => [
                [
                    'Name' => 'preferred_language',
                    'Value' => $this->user->preferred_language,
                ]
            ]
        ];

        $userNiches = NicheUser::select('niche_id')
            ->where('user_id', $this->user->id)
            ->get()
            ->pluck('niche_id');
        $niches = Niche::whereNotNull('code')->get();
        foreach ($niches as $niche) {
            $body['Data'][] = [
                'Name' => 'niche_' . $niche->code,
                'Value' => $userNiches->has($niche->id) ? true : false,
            ];
        }

        $response = $client->put(Resources::$Contactdata, ['id' => $this->user->mailjet_id, 'body' => $body]);
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->user->id;
    }
}
