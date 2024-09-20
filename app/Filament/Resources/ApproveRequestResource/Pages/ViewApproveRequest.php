<?php

namespace App\Filament\Resources\ApproveRequestResource\Pages;

use App\Enum\StatusRequest;
use App\Filament\Resources\ApproveRequestResource;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewApproveRequest extends ViewRecord
{
    protected static string $resource = ApproveRequestResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Get name
        $data['name'] = User::query()
            ->where('id', $data['user_id'])
            ->value('name');

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Approve')
                ->requiresConfirmation()
                ->icon('heroicon-m-hand-thumb-up')
                ->action(function () {
                    if (auth()->user()->isHeadOfDivision()) {
                        return $this->record->update(['status' => StatusRequest::One]);
                    } elseif (auth()->user()->isResource()) {
                        return $this->record->update(['status' => StatusRequest::Two]);
                    } elseif (auth()->user()->isDirector()) {
                        return $this->record->update(['status' => StatusRequest::Three]);
                    }
                })
        ];
    }
}
