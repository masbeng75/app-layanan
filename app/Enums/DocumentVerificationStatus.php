<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum DocumentVerificationStatus: string implements HasColor, HasLabel
{
    case PENDING = 'pending';
    case VALID = 'valid';
    case REVISION_NEEDED = 'revision_needed';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu Pemeriksaan',
            self::VALID => 'Sesuai / Valid',
            self::REVISION_NEEDED => 'Perlu Perbaikan',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::VALID => 'success',
            self::REVISION_NEEDED => 'warning',
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
