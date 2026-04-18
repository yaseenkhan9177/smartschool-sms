<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($actionType, $message)
    {
        $schoolId = null;
        $userId = null;

        // Determine user and school context
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $schoolId = $user->school_id;
            $userId = $user->id;
        } elseif (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $schoolId = $user->school_id;
            $userId = $user->id;
        } elseif (Auth::guard('accountant')->check()) {
            $user = Auth::guard('accountant')->user();
            $schoolId = $user->school_id; // Accountant stores school_id
            $userId = $user->id;
        } elseif (Auth::guard('student')->check()) {
            $user = Auth::guard('student')->user();
            $schoolId = $user->school_id;
            $userId = $user->id;
        }

        ActivityLog::create([
            'school_id' => $schoolId,
            'user_id' => $userId,
            'action_type' => $actionType,
            'message' => $message,
        ]);
    }
}
