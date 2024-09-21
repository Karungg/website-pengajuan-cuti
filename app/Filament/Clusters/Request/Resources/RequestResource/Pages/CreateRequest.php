<?php

namespace App\Filament\Clusters\Request\Resources\RequestResource\Pages;

use App\Filament\Clusters\Request\Resources\RequestResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRequest extends CreateRecord
{
    protected static string $resource = RequestResource::class;

    protected static ?string $title = 'Buat Pengajuan';

    protected static ?string $breadcrumb = 'Buat Pengajuan';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Check condition if in the city or out of city
        if ($data['condition']) {
            $data['location'] = 'Dalam Kota';
        }

        // If type request is leave, decrement it
        if ($data['type'] == 'leave') {
            User::query()->where('id', auth()->id())
                ->decrement('leave_allowance');
        }

        $data['user_id'] = auth()->id();

        return $data;
    }
}
