<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseKey extends Model
{
    protected $fillable = [
        'school_id',
        'license_key',
        'plan_duration',
        'start_date',
        'expiry_date',
        'status',
        'is_auto_generated',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expiry_date' => 'date',
        'is_auto_generated' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(User::class, 'school_id');
    }
}
