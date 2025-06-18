<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class TeacherController extends Controller
{
    /**
     * Get the authenticated teacher's profile
    */
    public function profile()
    {
        $teacher = Auth::user();
        Log::info('Retrieved teacher profile', ['teacher_id' => $teacher->id]);
        
        return response()->json([
            'teacher' => $teacher
        ]);
    }
}
