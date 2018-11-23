<?php

namespace App\Console\Commands\Download;

use App\Models\Download as DownloadModel;
use Illuminate\Console\Command;

class Status extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download jobs status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $downloads = DownloadModel::all();

        if (0 === $downloads->count()) {
            $this->info('No downloads job.');
        } else {
            $headers = ['Id', 'Status', 'Url'];

            $rows = $downloads->map(function (DownloadModel $download) {
                return [
                    'Id' => $download->id,
                    'Status' => $download->getStatusName(),
                    'Url' => $download->url
                ];
            });

            $this->table($headers, $rows);
        }
    }
}
