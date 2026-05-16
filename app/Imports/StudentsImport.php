<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\SchoolParent;
use App\Models\Family;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $schoolId;
    protected $lastSequence;

    public function __construct($schoolId)
    {
        $this->schoolId = $schoolId;
        
        // Initialize sequence for roll numbers
        $lastStudent = Student::where('school_id', $this->schoolId)
            ->latest('id')
            ->first();
            
        $this->lastSequence = 1000; // Will start at 1001
        if ($lastStudent && $lastStudent->roll_number) {
            $prefix = $this->schoolId . '00';
            if (strpos($lastStudent->roll_number, $prefix) === 0) {
                $this->lastSequence = (int) substr($lastStudent->roll_number, strlen($prefix));
            }
        }
    }

    public function model(array $row)
    {
        $this->lastSequence++;
        $rollNumber = $this->schoolId . '00' . $this->lastSequence;
        
        // Find or create class
        $class = SchoolClass::where('school_id', $this->schoolId)
            ->where('name', $row['class'])
            ->first();
            
        if (!$class) {
            $class = SchoolClass::create([
                'name' => $row['class'],
                'school_id' => $this->schoolId
            ]);
        }

        // Parent Logic
        $parentId = null;
        if (!empty($row['parent_phone'])) {
            $parent = SchoolParent::where('school_id', $this->schoolId)
                ->where('phone', $row['parent_phone'])
                ->first();
                
            if (!$parent) {
                $parent = SchoolParent::create([
                    'school_id' => $this->schoolId,
                    'name' => $row['parent_name'] ?? 'Parent',
                    'email' => $row['parent_email'] ?? null,
                    'phone' => $row['parent_phone'],
                    'password' => Hash::make($row['parent_phone']),
                ]);
            }
            $parentId = $parent->id;
        }

        // Family Logic
        $familyId = null;
        $fatherEmail = $row['father_email'] ?? $row['parent_email'] ?? null;
        if ($fatherEmail) {
            $family = Family::where('school_id', $this->schoolId)
                ->where('email', $fatherEmail)
                ->first();
                
            if (!$family) {
                $family = Family::create([
                    'family_code' => Family::generateCode(),
                    'father_name' => $row['father_name'] ?? $row['parent_name'] ?? 'Guardian',
                    'email' => $fatherEmail,
                    'phone' => $row['father_phone'] ?? $row['parent_phone'] ?? '',
                    'school_id' => $this->schoolId,
                ]);
            }
            $familyId = $family->id;
        }

        // Password generation: class_id + roll_number
        $plainPassword = $class->id . $rollNumber;

        return new Student([
            'name'           => $row['name'],
            'email'          => $row['email'],
            'gender'         => strtolower($row['gender'] ?? 'male'),
            'dob'            => $row['dob'] ?? null,
            'password'       => Hash::make($plainPassword),
            'plain_password' => $plainPassword,
            'phone'          => $row['phone'] ?? null,
            'roll_number'    => $rollNumber,
            'school_id'      => $this->schoolId,
            'status'         => 'approved',
            'parent_phone'   => $row['parent_phone'] ?? null,
            'parent_name'    => $row['parent_name'] ?? null,
            'parent_id'      => $parentId,
            'class_id'       => $class->id,
            'family_id'      => $familyId,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'class' => 'required',
            'parent_phone' => 'nullable',
        ];
    }
}
