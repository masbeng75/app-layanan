<?php

namespace App\Enums;

enum ComplaintStatus: string
{
    case RECEIVED = 'received';
    case VERIFICATION = 'verification';
    case CLARIFICATION_REQUESTED = 'clarification_requested';
    case DISPATCHED = 'dispatched';
    case IN_HANDLING = 'in_handling';
    case RESOLVED = 'resolved';
    case DUPLICATE = 'duplicate';
    case INVALID = 'invalid';

    public function label(): string
    {
        return match ($this) {
            self::RECEIVED => 'Laporan Diterima',
            self::VERIFICATION => 'Verifikasi Awal',
            self::CLARIFICATION_REQUESTED => 'Permintaan Klarifikasi',
            self::DISPATCHED => 'Didisposisikan',
            self::IN_HANDLING => 'Dalam Penanganan',
            self::RESOLVED => 'Selesai Ditangani',
            self::DUPLICATE => 'Laporan Duplikat',
            self::INVALID => 'Laporan Tidak Valid',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::RECEIVED => 'gray',
            self::VERIFICATION => 'info',
            self::CLARIFICATION_REQUESTED => 'warning',
            self::DISPATCHED => 'primary',
            self::IN_HANDLING => 'warning',
            self::RESOLVED => 'success',
            self::DUPLICATE, self::INVALID => 'danger',
        };
    }
}
