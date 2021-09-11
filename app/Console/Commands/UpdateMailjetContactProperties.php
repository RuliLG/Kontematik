<?php

namespace App\Console\Commands;

use App\Jobs\AddPropertiesToMailjet;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateMailjetContactProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailjet:contact-properties {userId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the contact properties of the provided user / all users. ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::whereNotNull('mailjet_id');
        if ($this->argument('userId')) {
            $users->where('id', $this->argument('userId'));
        }

        $users = $users->get();
        foreach ($users as $user) {
            dispatch(new AddPropertiesToMailjet($user));
        }
        return 0;
    }
}
