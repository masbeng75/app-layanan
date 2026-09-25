<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PublishStatus: string implements HasColor, HasLabel
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Draf',
            self::PUBLISHED => 'Diterbitkan',
            self::ARCHIVED => 'Diarsipkan',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PUBLISHED => 'success',
            self::ARCHIVED => 'warning',
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
