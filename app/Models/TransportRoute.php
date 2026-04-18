<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportRoute extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Scope for multi-tenancy if schools are used
    public function scopeForSchool($query)
    {
        // Assuming there is a global scope or we pass school_id manually.
        // For now, simple relationship.
        if (auth()->check() && auth()->user()->school_id) {
            return $query->where('school_id', auth()->user()->school_id);
        }
        return $query;
    }
}
