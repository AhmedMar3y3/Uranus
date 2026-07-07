<?php

namespace App\Console\Commands;

use App\Features\Presence\Services\PresenceService;
use App\Models\User;
use Illuminate\Console\Command;

class MarkStaleUsersOffline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presence:sweep';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark users offline if their last heartbeat is older than the configured threshold';

    public function handle(PresenceService $presence): int
    {
        $threshold = now()->subSeconds((int) config('presence.offline_threshold_seconds'));

        User::query()
            ->where('is_online', true)
            ->where('last_seen_at', '<', $threshold)
            ->cursor()
            ->each(fn (User $user) => $presence->markOfflineDueToTimeout($user));

        return self::SUCCESS;
    }
}
