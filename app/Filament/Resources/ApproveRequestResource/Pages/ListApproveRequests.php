<?php

namespace App\Filament\Resources\ApproveRequestResource\Pages;

use App\Filament\Resources\ApproveRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApproveRequests extends ListRecords
{
    protected static string $resource = ApproveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
