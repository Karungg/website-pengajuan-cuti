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
        // Get nip, name
        $user = User::query()
            ->findOrFail($data['user_id'], ['nip', 'name']);

        $data['nip'] = $user->nip;
        $data['name'] = $user->name;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Approve')
                ->requiresConfirmation()
                ->icon('heroicon-m-hand-thumb-up')
                ->hidden(function () {
                    $user = auth()->user();

                    if ($user->isHeadOfDivision() && in_array($this->record->status, [StatusRequest::One, StatusRequest::Four])) {
                        return true;
                    } elseif ($user->isResource() && in_array($this->record->status, [StatusRequest::Two, StatusRequest::Four])) {
                        return true;
                    } elseif ($user->isDirector() && in_array($this->record->status, [StatusRequest::One, StatusRequest::Three, StatusRequest::Four])) {
                        return true;
                    } elseif ($user->isDirector() && $this->record->user->roles[0]->name == 'employee' && $this->record->status == StatusRequest::Zero) {
                        return true;
                    }
                })
                ->action(function () {
                    $user = auth()->user();

                    if ($user->isHeadOfDivision()) {
                        $status = StatusRequest::One;
                    } elseif ($user->isResource()) {
                        $status = StatusRequest::Two;
                    } elseif ($user->isDirector()) {
                        $status = StatusRequest::Three;
                    }

                    return $this->record->update(['status' => $status]);
                }),
            Action::make('Tolak')
                ->requiresConfirmation()
                ->icon('heroicon-m-x-circle')
                ->color('danger')
                ->hidden(function () {
                    $user = auth()->user();


                    if ($user->isHeadOfDivision() && in_array($this->record->status, [StatusRequest::One, StatusRequest::Four])) {
                        return true;
                    } elseif ($user->isResource() && in_array($this->record->status, [StatusRequest::Two, StatusRequest::Four])) {
                        return true;
                    } elseif ($user->isDirector() && in_array($this->record->status, [StatusRequest::One, StatusRequest::Three, StatusRequest::Four])) {
                        return true;
                    } elseif ($user->isDirector() && $this->record->user->roles[0]->name == 'employee' && $this->record->status == StatusRequest::Zero) {
                        return true;
                    };
                })
                ->action(fn() => $this->record->update(['status' => StatusRequest::Four])),
        ];
    }
}
