<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ScheduleSmsController;

class ScheduleSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will handle Schedule SMS Setting';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
	    $this->ScheduleSmsController = new ScheduleSmsController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $this->ScheduleSmsController->scheduleSMSDailySnding();
	    $this->ScheduleSmsController->scheduleSMSWeeklySnding();
	    $this->ScheduleSmsController->scheduleSMSMonthlySnding();
	    $this->ScheduleSmsController->scheduleSMSOnceSnding();
//        \Log::info("This is my first Schedul command @ ". \Carbon\Carbon::now());
    }
}
