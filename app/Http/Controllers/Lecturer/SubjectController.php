<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::where('created_by', auth()->id())
            ->withCount(['classes', 'exams'])
            ->latest()
            ->get();

        return view('lecturer.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $classes = ClassModel::where('created_by', auth()->id())->get();
        return view('lecturer.subjects.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code|max:50',
            'description' => 'nullable|string',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:classes,id',
        ]);

        $validated['created_by'] = auth()->id();

        $subject = Subject::create($validated);

        if (isset($validated['class_ids'])) {
            $subject->classes()->attach($validated['class_ids']);
        }

        return redirect()->route('lecturer.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['classes', 'exams']);
        return view('lecturer.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $classes = ClassModel::where('created_by', auth()->id())->get();
        return view('lecturer.subjects.edit', compact('subject', 'classes'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:classes,id',
        ]);

        $subject->update($validated);

        if (isset($validated['class_ids'])) {
            $subject->classes()->sync($validated['class_ids']);
        }

        return redirect()->route('lecturer.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('lecturer.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}
