<?php

namespace App\Observers;

use App\Contracts\NotificationServiceInterface;
use App\Models\Position;

class PositionObserver
{
    public function __construct(protected NotificationServiceInterface $notificationService) {}
    /**
     * Handle the Position "created" event.
     */
    public function created(Position $position): void
    {
        $this->notificationService->sendSuccessNotification(
            'Jabatan berhasil ditambahkan.',
            auth()->user()->name . ' menambahkan jabatan baru.',
            $position,
            'filament.admin.resources.positions.index',
            'title',
            'admin'
        );
    }

    /**
     * Handle the Position "updated" event.
     */
    public function updated(Position $position): void
    {
        $this->notificationService->sendUpdateNotification(
            'Jabatan berhasil diupdate.',
            auth()->user()->name . ' mengubah jabatan.',
            $position,
            'filament.admin.resources.positions.index',
            'title',
            'admin'
        );
    }

    /**
     * Handle the Position "deleted" event.
     */
    public function deleted(Position $position): void
    {
        $this->notificationService->sendDeleteNotification(
            'Jabatan berhasil dihapus.',
            auth()->user()->name . ' menghapus jabatan.',
            'filament.admin.resources.positions.index',
            'admin'
        );
    }

    /**
     * Handle the Position "restored" event.
     */
    public function restored(Position $position): void
    {
        //
    }

    /**
     * Handle the Position "force deleted" event.
     */
    public function forceDeleted(Position $position): void
    {
        //
    }
}
