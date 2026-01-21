<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\StudentExam;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    //
    public function index()
    {
        $exams = Exam::where('created_by', auth()->id())
            ->with(['subject', 'studentExams.user'])
            ->withCount('studentExams')
            ->latest()
            ->get();

        return view('lecturer.results.index', compact('exams'));
    }

    public function examResults(Exam $exam)
    {
        $studentExams = StudentExam::where('exam_id', $exam->id)
            ->with(['user', 'exam'])
            ->where('status', '!=', 'in_progress')
            ->latest()
            ->get();

        $stats = [
            'total_attempts' => $studentExams->count(),
            'average_score' => $studentExams->avg('score'),
            'highest_score' => $studentExams->max('score'),
            'lowest_score' => $studentExams->min('score'),
            'pass_rate' => $studentExams->where('score', '>=', $exam->passing_marks)->count(),
        ];

        return view('lecturer.results.exam', compact('exam', 'studentExams', 'stats'));
    }

    public function studentExamDetail(StudentExam $studentExam)
    {
        $studentExam->load([
            'user',
            'exam.questions.options',
            'answers.question.options',
            'answers.selectedOption'
        ]);

        return view('lecturer.results.detail', compact('studentExam'));
    }

    public function gradeAnswer(Request $request, StudentAnswer $answer)
    {
        $validated = $request->validate([
            'marks_obtained' => 'required|integer|min:0|max:' . $answer->question->marks,
        ]);

        $answer->update([
            'marks_obtained' => $validated['marks_obtained'],
        ]);

        // Recalculate student exam score
        $studentExam = $answer->studentExam;
        $totalScore = $studentExam->answers()->sum('marks_obtained');
        
        $studentExam->update([
            'score' => $totalScore,
            'status' => 'graded',
        ]);

        return back()->with('success', 'Answer graded successfully.');
    }
}
