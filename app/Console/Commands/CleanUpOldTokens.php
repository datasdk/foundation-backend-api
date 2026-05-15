<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CleanUpOldTokens extends Command
{
    protected $signature = 'tokens:cleanup';

    protected $description = 'Delete personal access tokens not used in the last 3 months or created more than 1 week ago without being used';

    public function handle()
    {

        // Definer tidsgrænser for oprydningen
        $threeMonthsAgo = Carbon::now()->subMonths(3);

        $oneWeekAgo = Carbon::now()->subWeek();


        // Slet tokens som enten:
        // 1. Ikke er blevet brugt inden for de sidste 3 måneder (last_used_at ældre end 3 mdr)
        // 2. Eller hvor last_used_at er null og created_at er ældre end 1 uge
        $deletedRows = DB::table('personal_access_tokens')
            ->where('last_used_at', '<', $threeMonthsAgo)
            ->orWhere(function ($query) use ($oneWeekAgo) {
                $query->whereNull('last_used_at')
                      ->where('created_at', '<', $oneWeekAgo);
            })
            ->delete();


        $this->info("Cleanup complete. Deleted $deletedRows old tokens.");
        
    }
}
