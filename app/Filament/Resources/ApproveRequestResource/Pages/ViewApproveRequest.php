<?php

namespace App\Filament\Resources\ApproveRequestResource\Pages;

use App\Enum\StatusRequest;
use App\Filament\Resources\ApproveRequestResource;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;

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
            $this->buildApproveAction(),
            $this->buildRejectAction()
        ];
    }

    protected function buildApproveAction(): Action
    {
        return Action::make('Approve')
            ->requiresConfirmation()
            ->icon('heroicon-m-hand-thumb-up')
            ->hidden(fn() => $this->isApprovalHidden())
            ->action(fn() => $this->approveAction());
    }

    protected function buildRejectAction(): Action
    {
        return Action::make('Tolak')
            ->requiresConfirmation()
            ->icon('heroicon-m-x-circle')
            ->color('danger')
            ->hidden(fn() => $this->isApprovalHidden())
            ->action(fn() => $this->rejectAction());
    }

    protected function isApprovalHidden(): bool
    {
        $user = auth()->user();
        $status = $this->record->status;

        if ($user->isHeadOfDivision() && in_array($status, [StatusRequest::One, StatusRequest::Four])) {
            return true;
        }
        if ($user->isResource() && in_array($status, [StatusRequest::Two, StatusRequest::Four])) {
            return true;
        }
        if ($user->isDirector() && (in_array($status, [StatusRequest::One, StatusRequest::Three, StatusRequest::Four]) ||
            ($this->record->user->roles[0]->name === 'employee' && $status === StatusRequest::Zero))) {
            return true;
        }
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    protected function approveAction()
    {
        $user = auth()->user();

        $status =  match (true) {
            $user->isHeadOfDivision() => StatusRequest::One,
            $user->isResource() => StatusRequest::Two,
            $user->isDirector() => StatusRequest::Three
        };

        return $this->record->update(['status' => $status]);
    }

    protected function rejectAction()
    {
        // Get different days
        $startDate = Carbon::parse($this->record->start_date);
        $endDate = Carbon::parse($this->record->end_date);
        $differentDays = $startDate->diffInDays($endDate);

        DB::table('users')->where('id', $this->record->user_id)->increment('leave_allowance', $differentDays);

        return $this->record->update(['status' => StatusRequest::Four]);
    }
}
