<?php

namespace App\Observers;

use App\Enum\StatusRequest;
use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RequestObserver
{
    /**
     * Handle the Request "created" event.
     */
    public function created(Request $request): void
    {
        $status = $this->getInitialStatus();
        $this->logStatus($request->id, $status);
    }

    /**
     * Handle the Request "updated" event.
     */
    public function updated(Request $request): void
    {
        $user = User::query()
            ->findOrFail($request->user_id, ['id']);

        if ($request->status == StatusRequest::Zero) {
            $this->logStatus($request->id, 'Melakukan Perubahan Data Pengajuan');
        } else {
            $status = match ($request->status) {
                StatusRequest::One => 'Disetujui Kepala Divisi',
                StatusRequest::Two => 'Disetujui SDM',
                StatusRequest::Three => 'Disetujui Direktur Utama',
                StatusRequest::Four => 'Ditolak',
            };

            $this->logStatus($request->id, $status);

            if ($status === 'Disetujui Kepala Divisi') {
                $this->logStatus($request->id, 'Menunggu Disetujui SDM', 1);
            } elseif ($status === 'Disetujui SDM' && $user->isHeadOfDivision()) {
                $this->logStatus($request->id, 'Menunggu Disetujui Direktur Utama', 1);
            }
        }
    }

    /**
     * Log the status of a request.
     */
    private function logStatus(string $requestId, string $status, ?int $seconds = null): void
    {
        DB::table('request_logs')->insert([
            'id' => Str::uuid(),
            'status' => $status,
            'request_id' => $requestId,
            'user_id' => auth()->id(),
            'created_at' => $seconds ? now()->addSeconds(2) : now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Get the initial status based on the user role.
     */
    private function getInitialStatus(): string
    {
        $user = auth()->user();

        return match (true) {
            $user->isEmployee() => 'Menunggu Disetujui Kepala Divisi',
            $user->isHeadOfDivision() => 'Menunggu Disetujui SDM',
            $user->isResource() => 'Menunggu Disetujui Direktur Utama',
        };
    }
}
