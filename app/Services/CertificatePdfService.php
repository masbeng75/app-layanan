<?php

namespace App\Services;

use App\Models\DtsenCertificate;
use App\Models\PbiReactivation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificatePdfService
{
    /**
     * Generate PDF for SK DTSEN Certificate with embedded verification QR Code.
     */
    public static function generateDtsenPdf(DtsenCertificate $certificate): string
    {
        $verificationUrl = route('verification.show', [
            'verification_code' => $certificate->verification_code,
        ]);

        $qrSvg = QrCode::format('svg')->size(100)->errorCorrection('M')->generate($verificationUrl);
        $qrCodeBase64 = 'data:image/svg+xml;base64,'.base64_encode($qrSvg);

        $pdf = Pdf::loadView('pdf.dtsen-certificate', [
            'certificate' => $certificate,
            'qrCodeBase64' => $qrCodeBase64,
            'signerName' => $certificate->signer?->name ?? 'Drs. BAMBANG HERMANTO, M.Si.',
            'signerNip' => '196804151993031005',
        ]);

        $filePath = 'certificates/sk-dtsen-'.$certificate->id.'.pdf';
        Storage::disk('local')->put($filePath, $pdf->output());

        $certificate->update(['file_path' => $filePath]);

        return $filePath;
    }

    /**
     * Generate PDF for PBI-JK Recommendation Letter with embedded verification QR Code.
     */
    public static function generatePbiPdf(PbiReactivation $pbi): string
    {
        $verificationUrl = route('verification.show', [
            'verification_code' => $pbi->recommendation_number ?? $pbi->serviceRequest?->request_number,
        ]);

        $qrSvg = QrCode::format('svg')->size(100)->errorCorrection('M')->generate($verificationUrl);
        $qrCodeBase64 = 'data:image/svg+xml;base64,'.base64_encode($qrSvg);

        $pdf = Pdf::loadView('pdf.pbi-recommendation', [
            'pbi' => $pbi,
            'qrCodeBase64' => $qrCodeBase64,
            'signerName' => $pbi->signer?->name ?? 'Drs. BAMBANG HERMANTO, M.Si.',
            'signerNip' => '196804151993031005',
        ]);

        $filePath = 'certificates/rekomendasi-pbi-'.$pbi->id.'.pdf';
        Storage::disk('local')->put($filePath, $pdf->output());

        return $filePath;
    }
}
