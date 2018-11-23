<?php

namespace Tests\Feature\Console;

use App\Jobs\Download as DownloadJob;
use App\Models\Download as DownloadModel;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DownloadTest extends TestCase
{
    /**
     * Tests artisan download:status.
     *
     * @return void
     */
    public function testStatusCommand(): void
    {
        $this->artisan('download:status')->assertExitCode(0);
    }

    /**
     * Tests artisan download:add {url}.
     *
     * @return void
     */
    public function testAddCommand(): void
    {
        Queue::fake();

        $url = 'https://google.com/?_=' . time();

        $this->artisan('download:add', ['url' => $url])
            ->assertExitCode(0)
            ->expectsOutput('Job download ' . $url . ' created.');

        Queue::assertPushed(DownloadJob::class, function ($job) use ($url) {
            $result = $url === $job->download->url && DownloadModel::DOWNLOAD_STATUS_PENDING === $job->download->status;
            DownloadModel::destroy($job->download->id);
            return $result;
        });
    }

    /**
     * Tests POST /api/downloads.
     *
     * @return void
     */
    public function testAddErrorUrl(): void
    {
        $url = 'google';

        $this->artisan('download:add', ['url' => $url])
            ->assertExitCode(0)
            ->expectsOutput('The url format is invalid.');
    }
}
