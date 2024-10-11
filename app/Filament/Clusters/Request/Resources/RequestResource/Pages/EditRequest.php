<?php

namespace App\Filament\Clusters\Request\Resources\RequestResource\Pages;

use App\Filament\Clusters\Request\Resources\RequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequest extends EditRecord
{
    protected static string $resource = RequestResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Location
        if (isset($data['location']) && $data['location'] == 'Dalam Kota') {
            $data['condition'] = true;
        } else {
            $data['condition'] = false;
        }

        return $data;
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }
}
