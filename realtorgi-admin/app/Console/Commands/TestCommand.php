<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronTest;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'asdasdasd';

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
        // CronTest::create([
        //     'text' => 'Cron has been added'
        // ]);

        // $this->info('Sucess');

        return 0;
    }
}
