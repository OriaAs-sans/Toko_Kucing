<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchOrdersJob;

class SyncOrdersCommand extends Command
{
    protected $signature = 'catshop:sync-orders';
    protected $description = 'Fetch orders from configured marketplace channels and import them';

    public function handle()
    {
        // Dispatch job immediately (synchronously) to import now
        dispatch_sync(new FetchOrdersJob());
        $this->info('FetchOrdersJob executed');
        return 0;
    }
}
