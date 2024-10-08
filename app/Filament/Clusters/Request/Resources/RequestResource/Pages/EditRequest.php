<?php

namespace App\Filament\Clusters\Request\Resources\RequestResource\Pages;

use App\Filament\Clusters\Request\Resources\RequestResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditRequest extends EditRecord
{
    protected static string $resource = RequestResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        DB::beginTransaction();

        try {
            // Get different days
            $startDate = Carbon::parse($data['start_date'])->addDay(-1);
            $endDate = Carbon::parse($data['end_date']);
            $differentDays = $startDate->diffInDays($endDate);

            // Increment leaveAllowance if type is leave
            if ($data['type'] == 'leave') {
                DB::table('users')->where('id', auth()->id())->increment('leave_allowance', $differentDays);
            }

            // Location
            if (isset($data['location']) && $data['location'] == 'Dalam Kota') {
                $data['condition'] = true;
            } else {
                $data['condition'] = false;
            }

            return $data;
        } catch (\Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave()
    {
        // Get different days
        $startDate = Carbon::parse($this->record->start_date)->addDay(-1);
        $endDate = Carbon::parse($this->record->end_date);
        $differentDays = $startDate->diffInDays($endDate);

        // Increment leaveAllowance if type is leave
        if ($this->record->type == 'leave') {
            DB::table('users')->where('id', auth()->id())->decrement('leave_allowance', $differentDays);
        }

        DB::commit();
    }
}
