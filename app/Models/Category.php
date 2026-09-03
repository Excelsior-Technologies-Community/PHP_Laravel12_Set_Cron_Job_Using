<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * Check if category is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if category is inactive.
     */
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }
}
