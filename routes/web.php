<?php

use App\Http\Controllers\DocumentDownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/documents/service-request/{document}/download', [DocumentDownloadController::class, 'downloadServiceRequestDocument'])
    ->name('documents.service-request.download');

Route::get('/documents/complaint-attachment/{attachment}/download', [DocumentDownloadController::class, 'downloadComplaintAttachment'])
    ->name('documents.complaint-attachment.download');
