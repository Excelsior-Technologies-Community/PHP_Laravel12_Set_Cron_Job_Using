<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronJobLog extends Model
{
    protected $fillable = [
        'job_name',
        'status',
        'records_found',
        'records_deleted',
        'started_at',
        'completed_at',
        'duration_ms',
        'error_message',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Check whether this execution was successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Check whether this execution failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}