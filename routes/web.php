<?php

use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\DocumentDownloadController;
use App\Livewire\Portal\CertificateVerification;
use App\Livewire\Portal\ComplaintSubmission;
use App\Livewire\Portal\Home;
use App\Livewire\Portal\ServiceCatalog;
use App\Livewire\Portal\ServiceDetail;
use App\Livewire\Portal\ServiceRequestSubmission;
use App\Livewire\Portal\TicketTracking;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Portal Publik SAPA SOSIAL (Kabupaten Blitar)
|--------------------------------------------------------------------------
*/

Route::get('/', Home::class)->name('portal.home');
Route::get('/layanan', ServiceCatalog::class)->name('portal.catalog');
Route::get('/layanan/ajukan', ServiceRequestSubmission::class)->name('portal.request');
Route::get('/layanan/{slug}', ServiceDetail::class)->name('portal.detail');
Route::get('/pengaduan', ComplaintSubmission::class)->name('portal.complaint');
Route::get('/cek-status', TicketTracking::class)->name('portal.track');
Route::get('/verifikasi', CertificateVerification::class)->name('portal.verification');

// Direct Document Verification by QR / URL Parameter
Route::get('/verifikasi/{verification_code}', [CertificateVerificationController::class, 'verify'])
    ->name('verification.show');

// Signed Download Routes
Route::get('/documents/service-request/{document}/download', [DocumentDownloadController::class, 'downloadServiceRequestDocument'])
    ->name('documents.service-request.download');

Route::get('/documents/complaint-attachment/{attachment}/download', [DocumentDownloadController::class, 'downloadComplaintAttachment'])
    ->name('documents.complaint-attachment.download');
