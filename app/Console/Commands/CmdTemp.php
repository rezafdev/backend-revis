<?php

namespace App\Console\Commands;

use App\Models\MysqlModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CmdTemp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temp:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Temp Command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = Storage::drive('public')->allFiles('f/people');
        return CommandAlias::SUCCESS;
    }
}
