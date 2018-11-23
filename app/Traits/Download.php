<?php

namespace App\Traits;

use App\Jobs\Download as DownloadJob;
use App\Models\Download as DownloadModel;
use Illuminate\Support\Facades\Validator;

trait Download
{
    public function job($url): array
    {
        // Validate url
        $validator = Validator::make(
            [
                'url' => $url
            ],
            [
                'url' => 'required|url'
            ]
        );

        if ($validator->fails()) {
            // Echo errors
            $errors = [];
            foreach ($validator->errors()->all() as $error) {
                $errors[] = $error;
            }
            return [
                'status' => 400,
                'data' => $errors
            ];
        }

        // Create download record
        $download = DownloadModel::create(['url' => $url, 'status' => DownloadModel::DOWNLOAD_STATUS_PENDING]);
        // Add download job to queue
        DownloadJob::dispatch($download);

        return [
            'status' => 200,
            'data' => $download->id
        ];
    }

    public function send($id)
    {
        if (null !== ($download = DownloadModel::find($id))
            && DownloadModel::DOWNLOAD_STATUS_COMPLETE === $download->status
            && \Storage::disk('public')->exists($download->id)) {

            $pi = pathinfo($download->url);
            $mime = \File::mimeType(storage_path("app/public/{$download->id}"));
            $ext = substr($mime, strrpos($mime, '/') + 1);

            return response()->download(
                storage_path("app/public/{$download->id}"), str_slug($pi['filename']) . '.' . $ext
            );

        }

        return false;
    }
}
