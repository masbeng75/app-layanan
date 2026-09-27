<?php

namespace App\Http\Controllers;

use App\Models\DtsenCertificate;
use App\Models\PbiReactivation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class CertificateVerificationController extends Controller
{
    /**
     * Verify the authenticity and validity of an issued certificate or recommendation.
     */
    public function verify(Request $request, string $verification_code): View
    {
        // 1. Search for DTSEN Certificate
        $dtsen = DtsenCertificate::query()
            ->with(['serviceRequest.village.district', 'purpose', 'signer'])
            ->where('verification_code', $verification_code)
            ->orWhere('certificate_number', $verification_code)
            ->first();

        if ($dtsen) {
            $isExpired = $dtsen->valid_until && Carbon::parse($dtsen->valid_until)->isPast();
            $isValid = $dtsen->issued_at !== null && ! $isExpired;

            return view('portal.verify-certificate', [
                'type' => 'dtsen',
                'certificate' => $dtsen,
                'isValid' => $isValid,
                'isExpired' => $isExpired,
                'documentTitle' => 'Surat Keterangan Data Terpadu Sosial Ekonomi Nasional (DTSEN)',
                'documentNumber' => $dtsen->certificate_number,
                'subjectName' => $dtsen->subject_name,
                'maskedNik' => substr($dtsen->subject_nik, 0, 6).'******'.substr($dtsen->subject_nik, -4),
                'purpose' => $dtsen->purpose?->name ?? $dtsen->purpose_description ?? '-',
                'issuedAt' => $dtsen->issued_at,
                'validUntil' => $dtsen->valid_until,
                'signerName' => $dtsen->signer?->name ?? 'Kepala Dinas Sosial Kabupaten Blitar',
                'decile' => $dtsen->decile,
            ]);
        }

        // 2. Search for PBI Recommendation
        $pbi = PbiReactivation::query()
            ->with(['serviceRequest.village.district', 'signer'])
            ->where('recommendation_number', $verification_code)
            ->orWhereHas('serviceRequest', fn ($q) => $q->where('request_number', $verification_code))
            ->first();

        if ($pbi && $pbi->recommendation_issued_at) {
            return view('portal.verify-certificate', [
                'type' => 'pbi',
                'certificate' => $pbi,
                'isValid' => true,
                'isExpired' => false,
                'documentTitle' => 'Surat Rekomendasi Reaktivasi Peserta Bantuan Iuran Jaminan Kesehatan (PBI-JK)',
                'documentNumber' => $pbi->recommendation_number,
                'subjectName' => $pbi->participant_name,
                'maskedNik' => substr($pbi->participant_nik, 0, 6).'******'.substr($pbi->participant_nik, -4),
                'purpose' => 'Reaktivasi BPJS Kesehatan PBI-JK (Alasan: '.($pbi->reason?->label() ?? $pbi->reason).')',
                'issuedAt' => $pbi->recommendation_issued_at,
                'validUntil' => null,
                'signerName' => $pbi->signer?->name ?? 'Kepala Dinas Sosial Kabupaten Blitar',
                'decile' => $pbi->decile,
            ]);
        }

        // 3. Not Found
        return view('portal.verify-certificate', [
            'type' => null,
            'certificate' => null,
            'isValid' => false,
            'isExpired' => false,
            'verificationCode' => $verification_code,
        ]);
    }
}
