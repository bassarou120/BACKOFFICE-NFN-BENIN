<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckAdminCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkAdmin:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Cron Job running at ". now());
    }
}
