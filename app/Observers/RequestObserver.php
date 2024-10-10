<?php

namespace App\Observers;

use App\Enum\StatusRequest;
use App\Models\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RequestObserver
{
    /**
     * Handle the Request "created" event.
     */
    public function created(Request $request): void
    {
        $user = auth()->user();

        $status = match (true) {
            $user->isEmployee() => 'Menunggu Disetujui Kepala Divisi',
            $user->isHeadOfDivision() => 'Menunggu Disetujui Direktur',
            $user->isResource() => 'Menunggu Disetujui Direktur',
        };

        DB::table('request_logs')->insert([
            'id' => Str::uuid(),
            'status' => $status,
            'request_id' => $request->id,
            'user_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Handle the Request "updated" event.
     */
    public function updated(Request $request): void
    {
        $status = match ($request->status) {
            StatusRequest::One => 'Disetujui Kepala Divisi',
            StatusRequest::Two => 'Disetujui SDM',
            StatusRequest::Three => 'Disetujui Direksi',
            StatusRequest::Four => 'Ditolak'
        };

        DB::table('request_logs')->insert([
            'id' => Str::uuid(),
            'status' => $status,
            'request_id' => $request->id,
            'user_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
