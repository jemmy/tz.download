<?php

namespace Tests\Feature\Web;

use App\Jobs\Download as DownloadJob;
use App\Models\Download as DownloadModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DownloadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Tests GET /.
     *
     * @return void
     */
    public function testIndex(): void
    {
        $response = $this->call('GET', '/');
        $response->assertViewIs('index');
    }

    /**
     * Tests add download
     *
     * @return void
     */
    public function testAdd(): void
    {
        Queue::fake();

        $url = 'https://google.com/?_=' . time();

        $response = $this->post('/add', ['url' => $url]);

        $response->assertStatus(302);

        Queue::assertPushed(DownloadJob::class, function ($job) use ($url) {
            $result = $url === $job->download->url && DownloadModel::DOWNLOAD_STATUS_PENDING === $job->download->status;
            DownloadModel::destroy($job->download->id);
            return $result;
        });

        $this->artisan('download:status')->assertExitCode(0);
    }

    /**
     * Tests POST /api/downloads.
     *
     * @return void
     */
    public function testAddErrorUrl(): void
    {
        $url = 'google';
        $response = $this->post('/add', ['url' => $url]);
        $response->assertStatus(302);
    }

    /**
     * Tests POST /api/downloads.
     *
     * @return void
     */
    public function testDownloadErrorFile(): void
    {
        $response = $this->json('GET', '/download/0');
        $response->assertStatus(404);
    }

}
