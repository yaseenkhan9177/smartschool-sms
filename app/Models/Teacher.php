<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'subject',
        'semester',
        'image',
        'school_id',
        'id',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\SchoolScope);
    }
    public function schoolClasses()
    {
        return $this->belongsToMany(SchoolClass::class, 'school_class_teacher');
    }
}
