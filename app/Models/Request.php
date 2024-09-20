<?php

namespace App\Models;

use App\Enum\StatusRequest;
use App\Enum\TypeRequest;
use App\Observers\RequestObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(RequestObserver::class)]
class Request extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'description',
        'status',
        'user_id'
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'type' => TypeRequest::class,
            'status' => StatusRequest::class
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requestLogs(): HasMany
    {
        return $this->hasMany(RequestLog::class);
    }
}
