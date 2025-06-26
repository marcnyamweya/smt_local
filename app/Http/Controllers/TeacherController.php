<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


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

    public function update(Request $request)
    {
        $teacher = Teacher::findOrFail(Auth::id());
        // $this->authorize('update', $teacher);
        Log::info('Updating teacher profile', [
            'teacher_id' => $teacher->id,
            'data' => $request->all()
        ]);
        $teacher->update($request->only('name', 'email', 'phone', ));
        return $teacher;
    }

    public function uploadProfilePicture(Request $request)
    {
        $teacher = Teacher::findOrFail(Auth::id());

        $request->validate([
            'picture' => 'required|image|max:2048', // max 2MB
        ]);

        $file = $request->file('picture');
        $path = $file->store('profile_pictures', 'public');

        // Check if the teacher already has a profile picture and delete it
        if ($teacher->profilePicture) {
            Storage::disk('public')->delete($teacher->profilePicture->filename);
            $teacher->profilePicture()->delete();
        }

        // Create a new profile picture record for the teacher
        $teacher->profilePicture()->create([
            'filename' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return response()->json(['message' => 'Profile picture uploaded successfully.']);
    }


}
