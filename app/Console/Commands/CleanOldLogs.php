<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;


class CleanOldLogs extends Command
{

    protected $signature = 'logs:clean-old';

    protected $description = 'Delete log files older than 7 days';

    
    public function handle()
    {


        $logPath = storage_path('logs'); // typisk logmappe

        $files = File::files($logPath);

        $deletedCount = 0;


        foreach ($files as $file) {

            if ($file->getExtension() === 'log') {

                $lastModified = Carbon::createFromTimestamp($file->getMTime());

                if ($lastModified->lt(Carbon::now()->subDays(30))) {
                    File::delete($file->getPathname());
                    $deletedCount++;
                }

            }

        }


        $this->info("Deleted {$deletedCount} log files older than 7 days.");

    }
}
