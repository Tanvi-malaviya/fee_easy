<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\DemoAccountSeeder;

class SeedDemoAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed or refresh the showcase Demo Institute account with comprehensive dummy data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Seeding Demo Account with full dummy dataset...');
        $seeder = new DemoAccountSeeder();
        $seeder->setCommand($this);
        $seeder->run();

        return Command::SUCCESS;
    }
}
