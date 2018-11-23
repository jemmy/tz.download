<?php

namespace Tests\Feature\Api;

use App\Jobs\Download as DownloadJob;
use App\Models\Download as DownloadModel;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DownloadTest extends TestCase
{
    /**
     * Tests GET /api/downloads.
     *
     * @return void
     */
    public function testIndex(): void
    {
        $response = $this->json('GET', '/api/downloads');
        $response->assertStatus(200)->assertJson(['data' => []]);
    }

    /**
     * Tests POST /api/downloads.
     *
     * @return void
     */
    public function testAdd(): void
    {
        Queue::fake();

        $url = 'https://google.com/?_=' . time();

        $response = $this->json('POST', '/api/downloads', ['url' => $url]);
        $response->assertStatus(201);

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
        $response = $this->json('POST', '/api/downloads', ['url' => $url]);
        $response->assertStatus(400)->assertJson(['errors' => []]);
    }

    /**
     * Tests POST /api/downloads.
     *
     * @return void
     */
    public function testDownloadErrorFile(): void
    {
        $response = $this->json('GET', '/api/downloads/0');
        $response->assertStatus(404)->assertJson(['errors' => []]);
    }


}
