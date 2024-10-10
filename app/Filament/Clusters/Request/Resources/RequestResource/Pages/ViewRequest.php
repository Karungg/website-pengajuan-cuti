<?php

namespace App\Filament\Clusters\Request\Resources\RequestResource\Pages;

use App\Enum\StatusRequest;
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
            Action::make('Lihat Timeline')
                ->url(route('filament.admin.request.resources.requests.timeline', ['record' => $this->record->id])),
            Actions\EditAction::make(),
            $this->downloadAction()
        ];
    }

    protected function downloadAction()
    {
        return Action::make('Unduh Dokumen')
            ->url(route('pdf', $this->record->id))
            ->openUrlInNewTab()
            ->visible(fn() => $this->record->status == StatusRequest::Three);
    }
}
