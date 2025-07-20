<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupSessions extends Command
{
    protected $signature = 'sessions:cleanup';
    protected $description = 'Clean up expired sessions from the database';

    public function handle()
    {
        $this->info('Cleaning up expired sessions...');
        
        // Delete sessions that are expired (default Laravel session lifetime is 120 minutes)
        $affected = DB::table('sessions')
            ->where('last_activity', '<', Carbon::now()->subMinutes(120)->getTimestamp())
            ->delete();
            
        $this->info("Deleted {$affected} expired sessions.");
    }
}
