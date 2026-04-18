<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolRequest extends Model
{
    protected $fillable = [
        'school_name',
        'owner_name',
        'email',
        'phone',
        'address',
        'city',
        'student_count',
        'status',
        'remarks',
        'logo',
    ];
}
