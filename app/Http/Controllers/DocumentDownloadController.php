<?php

namespace App\Http\Controllers;

use App\Models\ComplaintAttachment;
use App\Models\ServiceRequestDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentDownloadController extends Controller
{
    /**
     * Download or view a service request document securely via signed URL or policy authorization.
     */
    public function downloadServiceRequestDocument(Request $request, ServiceRequestDocument $document): Response
    {
        $hasValidSignature = $request->hasValidSignature();
        $isAuthorizedUser = $request->user() && Gate::allows('view', $document->serviceRequest);

        if (! $hasValidSignature && ! $isAuthorizedUser) {
            abort(403, 'Akses ditolak atau tautan verifikasi telah kedaluwarsa.');
        }

        $disk = Storage::disk('local');
        if (! $disk->exists($document->file_path)) {
            abort(404, 'Berkas dokumen tidak ditemukan di penyimpanan.');
        }

        return $disk->download(
            $document->file_path,
            $document->original_name ?? basename($document->file_path)
        );
    }

    /**
     * Download or view a complaint attachment securely via signed URL or policy authorization.
     */
    public function downloadComplaintAttachment(Request $request, ComplaintAttachment $attachment): Response
    {
        $hasValidSignature = $request->hasValidSignature();
        $isAuthorizedUser = $request->user() && Gate::allows('view', $attachment->complaint);

        if (! $hasValidSignature && ! $isAuthorizedUser) {
            abort(403, 'Akses ditolak atau tautan verifikasi telah kedaluwarsa.');
        }

        $disk = Storage::disk('local');
        if (! $disk->exists($attachment->file_path)) {
            abort(404, 'Berkas lampiran tidak ditemukan di penyimpanan.');
        }

        return $disk->download($attachment->file_path);
    }
}
