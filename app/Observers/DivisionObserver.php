<?php

namespace App\Observers;

use App\Contracts\NotificationServiceInterface;
use App\Models\Division;

class DivisionObserver
{
    public function __construct(protected NotificationServiceInterface $notificationService) {}
    /**
     * Handle the Division "created" event.
     */
    public function created(Division $division): void
    {
        $this->notificationService->sendSuccessNotification(
            'Divisi berhasil ditambahkan.',
            auth()->user()->name . ' menambahkan divisi baru.',
            $division,
            'filament.admin.resources.divisions.index',
            'title',
            'admin'
        );
    }

    /**
     * Handle the Division "updated" event.
     */
    public function updated(Division $division): void
    {
        $this->notificationService->sendUpdateNotification(
            'Divisi berhasil diupdate.',
            auth()->user()->name . ' mengubah divisi.',
            $division,
            'filament.admin.resources.divisions.index',
            'title',
            'admin'
        );
    }

    /**
     * Handle the Division "deleted" event.
     */
    public function deleted(Division $division): void
    {
        $this->notificationService->sendDeleteNotification(
            'Divisi berhasil dihapus.',
            auth()->user()->name . ' menghapus divisi.',
            'filament.admin.resources.divisions.index',
            'admin'
        );
    }

    /**
     * Handle the Division "restored" event.
     */
    public function restored(Division $division): void
    {
        //
    }

    /**
     * Handle the Division "force deleted" event.
     */
    public function forceDeleted(Division $division): void
    {
        //
    }
}
