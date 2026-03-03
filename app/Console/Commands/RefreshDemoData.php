<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RefreshDemoData extends Command
{
    protected $signature = 'demo:refresh {--force : Force refresh without confirmation}';

    protected $description = 'Refresh demo data from demo-data folder';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Refresh demo data?')) {
            return self::FAILURE;
        }

        try {
            $this->info('Refreshing database...');
            File::copy(base_path('demo-data/database.sqlite'), database_path('database.sqlite'));

            $this->info('Refreshing public assets...');
            File::deleteDirectory(storage_path('app/public'), preserve: false);
            File::makeDirectory(storage_path('app/public'), 0755, true);
            File::copyDirectory(base_path('demo-data/public'), storage_path('app/public'));

            $this->info('Demo data refreshed successfully.');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to refresh demo data: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
