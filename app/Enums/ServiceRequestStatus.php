<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ServiceRequestStatus: string implements HasColor, HasLabel
{
    case SUBMITTED = 'submitted';
    case DOCUMENT_CHECK = 'document_check';
    case REVISION_REQUESTED = 'revision_requested';
    case DATA_VERIFICATION = 'data_verification';
    case ELIGIBILITY_VERIFICATION = 'eligibility_verification';
    case VERIFICATION = 'verification';
    case ASSESSMENT = 'assessment';
    case AWAITING_APPROVAL = 'awaiting_approval';
    case RECOMMENDATION_ISSUED = 'recommendation_issued';
    case PROPOSED_TO_MINISTRY = 'proposed_to_ministry';
    case MINISTRY_APPROVED = 'ministry_approved';
    case MINISTRY_REJECTED = 'ministry_rejected';
    case REACTIVATED = 'reactivated';
    case IN_PROCESS = 'in_process';
    case ISSUED = 'issued';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::SUBMITTED => 'Diajukan',
            self::DOCUMENT_CHECK => 'Pemeriksaan Berkas',
            self::REVISION_REQUESTED => 'Permintaan Perbaikan Berkas',
            self::DATA_VERIFICATION => 'Pengecekan Data SIKS-NG',
            self::ELIGIBILITY_VERIFICATION => 'Verifikasi Kelayakan',
            self::VERIFICATION => 'Verifikasi',
            self::ASSESSMENT => 'Assessment',
            self::AWAITING_APPROVAL => 'Menunggu Persetujuan / Tanda Tangan',
            self::RECOMMENDATION_ISSUED => 'Surat Rekomendasi Terbit',
            self::PROPOSED_TO_MINISTRY => 'Diusulkan ke Kemensos',
            self::MINISTRY_APPROVED => 'Disetujui Kemensos',
            self::MINISTRY_REJECTED => 'Ditolak Kemensos',
            self::REACTIVATED => 'Kepesertaan Aktif Kembali',
            self::IN_PROCESS => 'Sedang Diproses',
            self::ISSUED => 'Surat Keterangan Terbit',
            self::COMPLETED => 'Selesai',
            self::REJECTED => 'Ditolak',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::SUBMITTED => 'gray',
            self::DOCUMENT_CHECK, self::DATA_VERIFICATION, self::ELIGIBILITY_VERIFICATION, self::VERIFICATION, self::ASSESSMENT => 'info',
            self::REVISION_REQUESTED => 'warning',
            self::AWAITING_APPROVAL => 'warning',
            self::RECOMMENDATION_ISSUED, self::PROPOSED_TO_MINISTRY, self::IN_PROCESS => 'primary',
            self::MINISTRY_APPROVED, self::REACTIVATED, self::ISSUED, self::COMPLETED => 'success',
            self::MINISTRY_REJECTED, self::REJECTED => 'danger',
        };
    }

    public function label(): string
    {
        return $this->getLabel();
    }

    public function color(): string
    {
        return $this->getColor();
    }
}
