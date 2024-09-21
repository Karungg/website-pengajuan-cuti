<?php

namespace App\Filament\Clusters\Request\Resources\RequestResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $leaveAllowance = DB::table('users')
            ->where('id', auth()->id())
            ->value('leave_allowance');

        $totalRequest = DB::table('requests')
            ->where('user_id', auth()->id())
            ->count();

        $inProcess = DB::table('requests')
            ->where('user_id', auth()->id())
            ->where('status', '!=', 'three')
            ->where('status', '!=', 'four')
            ->count();

        $rejected = DB::table('requests')
            ->where('user_id', auth()->id())
            ->where('status', 'four')
            ->count();

        return [
            Stat::make('Sisa Cuti', $leaveAllowance),
            Stat::make('Jumlah Pengajuan', $totalRequest),
            Stat::make('Dalam Proses', $inProcess),
            Stat::make('Ditolak', $rejected),
        ];
    }
}
