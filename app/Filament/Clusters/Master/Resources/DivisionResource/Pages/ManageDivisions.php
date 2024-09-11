<?php

namespace App\Filament\Clusters\Master\Resources\DivisionResource\Pages;

use App\Filament\Clusters\Master\Resources\DivisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDivisions extends ManageRecords
{
    protected static string $resource = DivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
