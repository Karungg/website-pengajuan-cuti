<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Ubah Pegawai';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave()
    {
        $record = $this->record;
        $positionTitle = DB::table('positions')
            ->where('id', $record->position_id)
            ->value('title');

        return match ($positionTitle) {
            'Direksi' => $record->syncRoles('director'),
            'SDM' => $record->syncRoles('resource'),
            'Kepala Bagian' => $record->syncRoles('headOfDivision'),
            'Pegawai' => $record->syncRoles('employee'),
        };
    }
}
