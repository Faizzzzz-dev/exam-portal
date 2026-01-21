<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentExam;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    //
    public function index()
    {
        $results = StudentExam::where('user_id', auth()->id())
            ->where('status', '!=', 'in_progress')
            ->with(['exam.subject'])
            ->latest()
            ->paginate(10);

        return view('student.results.index', compact('results'));
    }

    public function show(StudentExam $studentExam)
    {
        // Validate ownership
        if ($studentExam->user_id !== auth()->id()) {
            abort(403);
        }

        $studentExam->load([
            'exam.questions.options',
            'answers.question.options',
            'answers.selectedOption'
        ]);

        return view('student.results.show', compact('studentExam'));
    }
}
