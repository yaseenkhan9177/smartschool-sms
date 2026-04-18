<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'school_id'];

    public function templates()
    {
        return $this->hasMany(CertificateTemplate::class, 'type_id');
    }
}
