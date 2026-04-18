<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'school_id',
        'title',
        'body',
        'footer_left',
        'footer_right',
        'background_image',
        'is_active'
    ];

    public function type()
    {
        return $this->belongsTo(CertificateType::class, 'type_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'template_id');
    }
}
