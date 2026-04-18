<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\SchoolScope;

class OnlineClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'teacher_id',
        'subject_id',
        'school_class_id',
        'topic',
        'start_time',
        'duration',
        'meeting_id',
        'meeting_password',
        'join_url',
        'start_url',
        'slides_path',
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new SchoolScope);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
}
