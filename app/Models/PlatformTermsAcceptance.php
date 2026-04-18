<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformTermsAcceptance extends Model
{
    protected $table = 'platform_terms_acceptance';
    protected $fillable = ['school_email', 'accepted_at', 'ip_address', 'terms_version', 'request_id'];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];
}
