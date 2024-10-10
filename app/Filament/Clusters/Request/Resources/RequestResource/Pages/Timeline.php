<?php

namespace App\Filament\Clusters\Request\Resources\RequestResource\Pages;

use App\Filament\Clusters\Request\Resources\RequestResource;
use App\Models\Request;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class Timeline extends Page
{
    use InteractsWithRecord;

    protected static string $resource = RequestResource::class;

    protected static string $view = 'filament.clusters.request.resources.request-resource.pages.timeline';

    public array $logs = [];

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->logs = DB::table('request_logs')
            ->where('request_id', $this->record->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
    }
}
