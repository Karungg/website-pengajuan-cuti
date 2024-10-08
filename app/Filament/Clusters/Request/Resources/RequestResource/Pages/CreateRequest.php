<?php

namespace App\Filament\Clusters\Request\Resources\RequestResource\Pages;

use App\Filament\Clusters\Request\Resources\RequestResource;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRequest extends CreateRecord
{
    protected static string $resource = RequestResource::class;

    protected static ?string $title = 'Buat Pengajuan';

    protected static ?string $breadcrumb = 'Buat Pengajuan';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);

        // Check condition if in the city or out of city
        if ($data['condition']) {
            $data['location'] = 'Dalam Kota';
        }

        $data['user_id'] = auth()->id();

        return $data;
    }
}
