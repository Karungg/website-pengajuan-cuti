<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'request_details';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'approve_by',
        'request_id'
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }
}
