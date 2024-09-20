<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusRequest: string implements HasLabel, HasColor, HasIcon
{
    case Zero = 'Zero';
    case One = 'One';
    case Two = 'Two';
    case Three = 'Three';
    case Four = 'Four';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Zero => 'Pending',
            self::One => 'Disetujui Kepala Divisi',
            self::Two => 'Disetujui SDM',
            self::Three => 'Disetujui Direktur',
            self::Four => 'Ditolak'
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Zero => 'gray',
            self::One => 'warning',
            self::Two => 'info',
            self::Three => 'success',
            self::Four => 'danger'
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Zero => 'heroicon-m-arrow-path',
            self::One => 'heroicon-m-hand-thumb-up',
            self::Two => 'heroicon-m-hand-thumb-up',
            self::Three => 'heroicon-m-hand-thumb-up',
            self::Four => 'heroicon-m-x-mark',
        };
    }
}
