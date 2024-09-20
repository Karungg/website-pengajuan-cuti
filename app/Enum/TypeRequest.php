<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TypeRequest: string implements HasLabel, HasColor, HasIcon
{
    case Leave = 'leave';
    case Permission = 'permission';
    case Sick = 'sick';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Leave => 'Cuti',
            self::Permission => 'Izin',
            self::Sick => 'Sakit',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Leave => 'primary',
            self::Permission => 'info',
            self::Sick => 'danger'
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Leave => 'heroicon-m-arrow-right-start-on-rectangle',
            self::Permission => 'heroicon-m-hand-raised',
            self::Sick => 'heroicon-m-user-minus'
        };
    }
}
