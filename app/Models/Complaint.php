<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'parent_id',
        'subject',
        'message',
        'status',
    ];

    public function parent()
    {
        return $this->belongsTo(SchoolParent::class); // Assuming SchoolParent is the model for parents table
    }
}
