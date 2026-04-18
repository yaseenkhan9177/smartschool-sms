<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'parent_id',
        'type',
        'from_date',
        'to_date',
        'reason',
        'status',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function parent()
    {
        return $this->belongsTo(SchoolParent::class); // SchoolParent model is used for 'parents' table often, checking context
        // Context check: Migration uses 'parents' table. 
        // User has 'SchoolParent.php' open, I should verify if that maps to 'parents' table.
    }
}
