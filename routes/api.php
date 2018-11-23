<?php

/*
Route::apiResource('downloads', 'Api\Download',
    ['only' => ['index', 'store', 'file']]
);
*/

Route::group(
    [
        'namespace' => 'Api',
        'as' => 'api.'

    ], function () {

    Route::get('downloads', 'Download@index')->name('api.download.index');
    Route::post('downloads', 'Download@add')->name('api.download.add');
    Route::get('downloads/{id}', 'Download@file')->name('api.download.file');
});







































