<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;

class RedisRemoveAllDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $res = $this->choice(
            'Flushing the cache does not respect your configured cache "prefix" and will remove all entries from the cache. Consider this carefully when clearing a cache which is shared by other applications. Do you want to remove cache?',
            ['yes', 'no'],
        );
        if ($res == 'yes') {
            Cache::flush();
            $this->comment("All Redis Cache removed");
        } else {
            $this->comment("Cancel removing");
        }
    }
}
