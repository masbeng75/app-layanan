<?php

namespace App\Livewire\Portal;

use App\Models\DtsenCertificate;
use App\Models\PbiReactivation;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Verifikasi Keaslian Dokumen — SAPA SOSIAL Kab. Blitar')]
class CertificateVerification extends Component
{
    #[Url(as: 'code')]
    public string $code = '';

    public bool $hasSearched = false;

    public ?string $resultType = null; // 'dtsen', 'pbi', or 'invalid'

    public ?string $documentTitle = null;

    public ?string $documentNumber = null;

    public ?string $subjectName = null;

    public ?string $maskedNik = null;

    public ?string $purpose = null;

    public ?string $issuedAt = null;

    public ?string $validUntil = null;

    public ?string $signerName = null;

    public ?int $decile = null;

    public bool $isValid = false;

    public bool $isExpired = false;

    public ?string $verificationCode = null;

    public function mount(): void
    {
        if (! empty($this->code)) {
            $this->verifyCode();
        }
    }

    public function verifyCode(): void
    {
        $this->validate([
            'code' => ['required', 'string', 'min:3'],
        ], [
            'code.required' => 'Masukkan kode verifikasi atau nomor surat.',
        ]);

        $this->hasSearched = true;
        $search = trim($this->code);

        // 1. Search DTSEN Certificate
        $dtsen = DtsenCertificate::query()
            ->with(['serviceRequest.village.district', 'purpose', 'signer'])
            ->where('verification_code', $search)
            ->orWhere('certificate_number', $search)
            ->first();

        if ($dtsen) {
            $this->isExpired = $dtsen->valid_until && Carbon::parse($dtsen->valid_until)->isPast();
            $this->isValid = $dtsen->issued_at !== null && ! $this->isExpired;
            $this->resultType = 'dtsen';
            $this->documentTitle = 'Surat Keterangan Data Terpadu Sosial Ekonomi Nasional (DTSEN)';
            $this->documentNumber = $dtsen->certificate_number;
            $this->subjectName = $dtsen->subject_name;
            $this->maskedNik = substr((string) $dtsen->subject_nik, 0, 6).'******'.substr((string) $dtsen->subject_nik, -4);
            $this->purpose = $dtsen->purpose?->name ?? $dtsen->purpose_description ?? '-';
            $this->issuedAt = $dtsen->issued_at ? Carbon::parse($dtsen->issued_at)->translatedFormat('d F Y H:i').' WIB' : '-';
            $this->validUntil = $dtsen->valid_until ? Carbon::parse($dtsen->valid_until)->translatedFormat('d F Y') : 'Sesuai Ketentuan yang Berlaku';
            $this->signerName = $dtsen->signer?->name ?? 'Kepala Dinas Sosial Kabupaten Blitar';
            $this->decile = $dtsen->decile;
            $this->verificationCode = $dtsen->verification_code;

            return;
        }

        // 2. Search PBI Recommendation
        $pbi = PbiReactivation::query()
            ->with(['serviceRequest.village.district', 'signer'])
            ->where('recommendation_number', $search)
            ->orWhereHas('serviceRequest', fn ($q) => $q->where('request_number', $search))
            ->first();

        if ($pbi && $pbi->recommendation_issued_at) {
            $this->isExpired = false;
            $this->isValid = true;
            $this->resultType = 'pbi';
            $this->documentTitle = 'Surat Rekomendasi Reaktivasi Peserta Bantuan Iuran Jaminan Kesehatan (PBI-JK)';
            $this->documentNumber = $pbi->recommendation_number;
            $this->subjectName = $pbi->participant_name;
            $this->maskedNik = substr((string) $pbi->participant_nik, 0, 6).'******'.substr((string) $pbi->participant_nik, -4);
            $this->purpose = 'Reaktivasi BPJS Kesehatan PBI-JK (Alasan: '.($pbi->reason?->label() ?? $pbi->reason).')';
            $this->issuedAt = Carbon::parse($pbi->recommendation_issued_at)->translatedFormat('d F Y H:i').' WIB';
            $this->validUntil = 'Sesuai Masa Berlaku Kepesertaan JKN-KIS';
            $this->signerName = $pbi->signer?->name ?? 'Kepala Dinas Sosial Kabupaten Blitar';
            $this->decile = $pbi->decile;
            $this->verificationCode = $pbi->recommendation_number;

            return;
        }

        // 3. Not Found
        $this->resultType = 'invalid';
        $this->isValid = false;
        $this->isExpired = false;
        $this->verificationCode = $search;
    }

    public function render()
    {
        return view('livewire.portal.certificate-verification');
    }
}
