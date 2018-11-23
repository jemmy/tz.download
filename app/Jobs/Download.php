<?php

namespace App\Jobs;

use App\Models\Download as DownloadModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class Download implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $download;

    /**
     * Create a new job instance.
     *
     * @param DownloadModel $download
     */
    public function __construct(DownloadModel $download)
    {
        $this->download = $download;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        // Update status downloading
        $this->download->setStatus(DownloadModel::DOWNLOAD_STATUS_DOWNLOADING);
        try {
            // Download content from url
            if (false !== $content = file_get_contents($this->download->url)) {
                // Store content to file as $download->id
                Storage::disk('public')->put($this->download->id, $content);
                // Update status complete
                $this->download->setStatus(DownloadModel::DOWNLOAD_STATUS_COMPLETE);
            } else {
                // Update status error
                $this->download->setStatus(DownloadModel::DOWNLOAD_STATUS_ERROR);
            }
        } catch (\Exception $e) {
            // Update status error
            $this->download->setStatus(DownloadModel::DOWNLOAD_STATUS_ERROR);
        }
    }
}
