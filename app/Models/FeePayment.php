<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    protected $fillable = ['student_fee_id', 'amount_paid', 'payment_date', 'payment_method', 'transaction_id', 'remarks', 'school_id'];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\SchoolScope);
    }

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }
}
