<?php

namespace App\Enums;

enum ApprovalDecision: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case RETURNED = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu Persetujuan',
            self::APPROVED => 'Disetujui',
            self::RETURNED => 'Dikembalikan / Revisi',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::RETURNED => 'danger',
        };
    }
}
