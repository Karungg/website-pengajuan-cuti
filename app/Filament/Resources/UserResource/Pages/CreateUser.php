<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Buat Pegawai Baru';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['phone'] = '+62' . $data['phone'];
        $data['leave_allowance'] = 12;

        return $data;
    }

    protected function afterCreate()
    {
        $record = $this->record;
        $positionTitle = DB::table('positions')
            ->where('id', $record->position_id)
            ->value('title');

        return match ($positionTitle) {
            'Direktur Utama' => $record->assignRole('director'),
            'Direktur' => $record->assignRole('admin'),
            'SDM' => $record->assignRole('resource'),
            'Kepala Bagian' => $record->assignRole('headOfDivision'),
            'Pegawai' => $record->assignRole('employee'),
            'Kepala Kas' => $record->assignRole('headOfDivision'),
        };
    }
}
