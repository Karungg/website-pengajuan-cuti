<?php

namespace App\Filament\Clusters\Request\Resources\RequestResource\Pages;

use App\Filament\Clusters\Request\Resources\RequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRequest extends CreateRecord
{
    protected static string $resource = RequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['condition']) {
            $data['location'] = 'Dalam Kota';
        }
        $data['user_id'] = auth()->id();

        return $data;
    }
}
