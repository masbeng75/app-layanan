<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ReferralStatus: string implements HasColor, HasLabel
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case ACCEPTED = 'accepted';
    case IN_SERVICE = 'in_service';
    case COMPLETED = 'completed';
    case DECLINED = 'declined';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Draf Rujukan',
            self::SENT => 'Dikirim ke Lembaga',
            self::ACCEPTED => 'Diterima Lembaga',
            self::IN_SERVICE => 'Dalam Pelayanan',
            self::COMPLETED => 'Rujukan Selesai',
            self::DECLINED => 'Ditolak Lembaga',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::SENT => 'info',
            self::ACCEPTED => 'warning',
            self::IN_SERVICE => 'primary',
            self::COMPLETED => 'success',
            self::DECLINED, self::CANCELLED => 'danger',
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
