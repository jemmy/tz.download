<?php

namespace Tests\Unit;

use App\Jobs\Download as DownloadJob;
use App\Models\Download as DownloadModel;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class jobTest extends TestCase
{

    public function testJob()
    {
        Storage::fake('public');

        $url = 'https://google.com';

        $download = factory(DownloadModel::class)->create([
            'url' => $url,
            'status' => DownloadModel::DOWNLOAD_STATUS_PENDING
        ]);

        $job = new DownloadJob($download);
        $job->handle();

        $this->assertEquals($download->id, $job->download->id);
        $this->assertEquals(DownloadModel::DOWNLOAD_STATUS_COMPLETE, $download->status);

        Storage::disk('public')->assertExists($download->id);

        Storage::disk('public')->delete($download->id);

        DownloadModel::destroy($job->download->id);
    }
}

