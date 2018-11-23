<?php

Route::get('/', 'Download@index')->name('download.index');
Route::post('/add', 'Download@add')->name('download.add');
Route::get('/download/{id}', 'Download@file')->name('download.file');
