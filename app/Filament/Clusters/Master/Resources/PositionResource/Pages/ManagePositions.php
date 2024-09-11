<?php

namespace App\Filament\Clusters\Master\Resources\PositionResource\Pages;

use App\Filament\Clusters\Master\Resources\PositionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePositions extends ManageRecords
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
