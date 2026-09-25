<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ComplaintStatus: string implements HasColor, HasLabel
{
    case RECEIVED = 'received';
    case VERIFICATION = 'verification';
    case CLARIFICATION_REQUESTED = 'clarification_requested';
    case DISPATCHED = 'dispatched';
    case IN_HANDLING = 'in_handling';
    case RESOLVED = 'resolved';
    case DUPLICATE = 'duplicate';
    case INVALID = 'invalid';

    public function getLabel(): string
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

    public function getColor(): string|array|null
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

    public function label(): string
    {
        return $this->getLabel();
    }

    public function color(): string
    {
        return $this->getColor();
    }
}
