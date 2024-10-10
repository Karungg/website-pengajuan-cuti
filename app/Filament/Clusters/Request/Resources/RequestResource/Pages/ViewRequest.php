<?php

namespace App\Filament\Clusters\Request\Resources\RequestResource\Pages;

use App\Filament\Clusters\Request\Resources\RequestResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewRequest extends ViewRecord
{
    protected static string $resource = RequestResource::class;

    protected static ?string $title = 'Lihat Pengajuan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            $this->downloadAction()
        ];
    }

    protected function downloadAction(): Action
    {
        return Action::make('Unduh Dokumen');
    }
}
