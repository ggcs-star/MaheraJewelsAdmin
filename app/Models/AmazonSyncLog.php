<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonSyncLog extends Model
{
    use HasFactory;

    protected $table = 'amazon_sync_logs';

    protected $fillable = [
        'module',
        'api_name',
        'http_method',
        'status',
        'message',
        'request_payload',
        'response_payload',
        'http_status',
        'exception',
        'execution_time',
        'synced_at',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
    ];
}