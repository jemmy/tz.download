<?php

namespace App\Http\Controllers;

use App\Models\Download as DownloadModel;
use App\Traits\Download as DownloadTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Download extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, DownloadTrait;

    public function index()
    {
        return view('index', ['downloads' => DownloadModel::all()]);
    }

    public function add()
    {
        $url = request('url');

        $result = $this->job($url);

        if (200 !== $result['status']) {
            return redirect('/')->with('errors', $result['data']);
        }

        return redirect('/')->with('info', 'Task added to queue');
    }

    public function file($id)
    {
        if (false === $result = $this->send($id)) {
            return response('', 404);
        }

        return $result;
    }
}
