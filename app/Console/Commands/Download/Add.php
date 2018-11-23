<?php

namespace App\Console\Commands\Download;

use App\Traits\Download as DownloadTrait;
use Illuminate\Console\Command;

class Add extends Command
{
    use DownloadTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:add {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating a download job by link ';

    /**
     * Execute the console command.
     */
    public function handle(): bool
    {
        // Url in arguments
        $url = $this->argument('url', null);

        $result = $this->job($url);

        if (200 !== $result['status']) {
            foreach ($result['data'] as $error) {
                $this->error($error);
            }
            return false;
        }

        $this->info('Job download ' . $url . ' created.');
        return true;
    }
}
