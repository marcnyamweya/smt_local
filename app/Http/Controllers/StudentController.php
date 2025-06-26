<?php

namespace App\Http\Controllers;


use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class StudentController extends Controller
{

    public function index(Request $request)
    {
        $students = $request->user()->students;
        Log::info('Retrieved students for user: ' . $request->user()->id, ['students' => $students]);
        return response()->json([
            'students' => $students->values()->toArray()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|integer',
            'age' => 'required|integer|min:1',
            'course' => 'nullable|string|max:255',
        ]);
        Log::info('Creating student for user: ' . $request->user()->id, ['data' => $request->all()]);
        return $request->user()->students()->create($request->only('name', 'email', 'age', 'course', 'phone'));
    }

    public function show(Student $student)
    {
        return $student;
    }

    public function update(Request $request, Student $student)
    {
        // $this->authorize('update', $student);
        $student->update($request->only('name', 'email'));
        return $student;
    }

    public function destroy(Student $student)
    {
        // $this->authorize('delete', $student);
        $student->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function uploadProfilePicture(Request $request, Student $student)
    {
        $request->validate([
            'picture' => 'required|image|max:2048', // max 2MB
        ]);

        $file = $request->file('picture');
        $path = $file->store('profile_pictures', 'public');

        if ($student->profilePicture) {
            Storage::disk('public')->delete($student->profilePicture->filename);
            $student->profilePicture()->delete();
        }

        $student->profilePicture()->create([
            'filename' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return response()->json(['message' => 'Profile picture uploaded successfully.']);
    }
}
