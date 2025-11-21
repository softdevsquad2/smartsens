<?php

namespace App\Console\Commands;

use App\Http\Controllers\AbsensiController;
use Illuminate\Console\Command;

class MarkAbsentStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:mark-absent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark students as absent who have not attended today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new AbsensiController;
        $controller->markAbsentStudents();

        $this->info('Absent students have been marked successfully.');
    }
}
