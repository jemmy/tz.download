<?php

namespace App\Http\Controllers\Api;

use App\Models\Download as DownloadModel;
use App\Traits\Download as DownloadTrait;
use Illuminate\Routing\Controller as BaseController;

class Download extends BaseController
{
    use DownloadTrait;

    public function index()
    {
        return [
            'data' => DownloadModel::all()->map(function (DownloadModel $download) {
                return [
                    'id' => $download->id,
                    'status' => [
                        'code' => $download->status,
                        'name' => $download->getStatusName()
                    ],
                    'url' => $download->url,
                    'download' => $download->status === DownloadModel::DOWNLOAD_STATUS_COMPLETE
                        ? '/download/' . $download->id
                        : null
                ];
            })
        ];
    }

    public function add()
    {
        $url = request()->json('url');

        $result = $this->job($url);

        if (200 !== $result['status']) {
            return response()->json(['errors' => $result['data']], $result['status']);
        }

        return response()->json([
            'data' => [
                'id' => $result['data']
            ],
            'message' => 'Job download ' . $url . ' created.'
        ], 201);
    }


}
