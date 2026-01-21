<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    //
    public function index()
    {
        $exams = Exam::where('created_by', auth()->id())
            ->with(['subject', 'classes'])
            ->withCount(['questions', 'studentExams'])
            ->latest()
            ->get();

        return view('lecturer.exam.index', compact('exams'));
    }

    public function create()
    {
        $subjects = Subject::where('created_by', auth()->id())->get();
        $classes = ClassModel::where('created_by', auth()->id())->get();

        return view('lecturer.exams.create', compact('subjects', 'classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'duration_minutes' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'shuffle_questions' => 'boolean',
            'max_attempts' => 'required|integer|min:1',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['shuffle_questions'] = $request->has('shuffle_questions');

        $exam = Exam::create($validated);

        return redirect()->route('lecturer.exams.show', $exam)
            ->with('success', 'Exam created successfully. Now add questions.');
    }

    public function show(Exam $exam)
    {
        $exam->load(['subject', 'questions.options', 'classes']);
        $classes = ClassModel::where('created_by', auth()->id())->get();

        return view('lecturer.exams.show', compact('exam', 'classes'));
    }

    public function edit(Exam $exam)
    {
        $subjects = Subject::where('created_by', auth()->id())->get();
        return view('lecturer.exams.edit', compact('exam', 'subjects'));
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'duration_minutes' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'shuffle_questions' => 'boolean',
            'max_attempts' => 'required|integer|min:1',
        ]);

        $validated['shuffle_questions'] = $request->has('shuffle_questions');

        $exam->update($validated);

        return redirect()->route('lecturer.exams.show', $exam)
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('lecturer.exams.index')
            ->with('success', 'Exam deleted successfully.');
    }

    public function publish(Exam $exam)
    {
        if ($exam->questions()->count() === 0) {
            return back()->with('error', 'Cannot publish exam without questions.');
        }

        $exam->update(['is_published' => true]);

        return back()->with('success', 'Exam published successfully.');
    }

    public function assign(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'class_ids' => 'required|array',
            'class_ids.*' => 'exists:classes,id',
        ]);

        $exam->classes()->sync($validated['class_ids']);

        return back()->with('success', 'Exam assigned to classes successfully.');
    }
}
