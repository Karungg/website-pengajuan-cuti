<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Request extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $title = 'Data Pengajuan';

    protected static ?string $navigationGroup = 'Transaksi Pengajuan';

    protected static ?int $navigationSort = 1;
}
