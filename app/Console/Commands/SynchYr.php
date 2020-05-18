<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use App\Api\Yr;
use Illuminate\Console\Command;

class SynchYr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yr:synch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        // Log::info("Yr import ".date("Y-m-d H:i:s" ) );
        $yr = new Yr();
        $yr->getForecast(date("Y-m-d"));
    }
}
