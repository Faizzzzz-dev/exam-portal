<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

use Illuminate\Http\Request;

class ClassController extends Controller
{
    //
    use AuthorizesRequests;

    public function index()
    {
        $classes = ClassModel::where('created_by', auth()->id())
            ->withCount(['students', 'subjects'])
            ->latest()
            ->get();

        return view('lecturer.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('lecturer.classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:classes,code|max:50',
            'description' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        ClassModel::create($validated);

        return redirect()->route('lecturer.classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(ClassModel $class)
    {
        $class->load(['students', 'subjects', 'exams']);
        
        // Get students not already enrolled in this class
        $availableStudents = User::join('roles', 'users.role_id', '=', 'roles.id')
    ->where('roles.name', 'student')
    ->whereNotIn('users.id', $class->students->pluck('id'))
    ->select('users.*')
    ->get();


        return view('lecturer.classes.show', compact('class', 'availableStudents'));
    }

    public function edit(ClassModel $class)
    {
        $this->authorize('update', $class);
        return view('lecturer.classes.edit', compact('class'));
    }

    public function update(Request $request, ClassModel $class)
    {
        $this->authorize('update', $class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:classes,code,' . $class->id,
            'description' => 'nullable|string',
        ]);

        $class->update($validated);

        return redirect()->route('lecturer.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassModel $class)
    {
        $this->authorize('delete', $class);
        $class->delete();

        return redirect()->route('lecturer.classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function addStudents(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        $class->students()->syncWithoutDetaching($validated['student_ids']);

        return back()->with('success', 'Students added successfully.');
    }

    public function removeStudent(ClassModel $class, User $student)
    {
        $class->students()->detach($student->id);

        return back()->with('success', 'Student removed successfully.');
    }
}
