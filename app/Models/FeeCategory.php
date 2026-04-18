<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeCategory extends Model
{
    protected $fillable = ['name', 'description', 'school_id'];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\SchoolScope);
    }

    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class);
    }
}
