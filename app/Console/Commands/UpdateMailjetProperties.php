<?php

namespace App\Console\Commands;

use App\Jobs\UpdateMailjetProperties as JobsUpdateMailjetProperties;
use Illuminate\Console\Command;

class UpdateMailjetProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailjet:properties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the mailjet command list by including every niche from the platform';

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
        dispatch_sync(new JobsUpdateMailjetProperties());
        return 0;
    }
}
