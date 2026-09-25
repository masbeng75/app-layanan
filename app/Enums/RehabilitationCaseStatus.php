<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RehabilitationCaseStatus: string implements HasColor, HasLabel
{
    case RECEIVED = 'received';
    case ASSESSMENT = 'assessment';
    case SERVICE_PLANNING = 'service_planning';
    case IN_SERVICE = 'in_service';
    case MONITORING = 'monitoring';
    case CLOSED = 'closed';

    public function getLabel(): string
    {
        return match ($this) {
            self::RECEIVED => 'Diterima',
            self::ASSESSMENT => 'Assessment',
            self::SERVICE_PLANNING => 'Rencana Pelayanan',
            self::IN_SERVICE => 'Dalam Pelayanan',
            self::MONITORING => 'Monitoring',
            self::CLOSED => 'Kasus Ditutup',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::RECEIVED => 'gray',
            self::ASSESSMENT, self::SERVICE_PLANNING => 'info',
            self::IN_SERVICE => 'warning',
            self::MONITORING => 'primary',
            self::CLOSED => 'success',
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
