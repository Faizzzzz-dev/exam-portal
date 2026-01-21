<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\StudentExam;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExamController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();
        $classIds = $user->classes->pluck('id');

        $exams = Exam::whereHas('classes', function ($query) use ($classIds) {
                $query->whereIn('classes.id', $classIds);
            })
            ->where('is_published', true)
            ->with(['subject', 'classes'])
            ->withCount('questions')
            ->get();

        return view('student.exams.index', compact('exams'));
    }

    public function show(Exam $exam)
    {
        $user = auth()->user();

        // Check if student has access
        $hasAccess = $exam->classes()
            ->whereIn('classes.id', $user->classes->pluck('id'))
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this exam.');
        }

        // Get student's attempts
        $attempts = StudentExam::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->where('status', '!=', 'in_progress')
            ->with('exam')
            ->get();

        // Check for in-progress exam
        $inProgress = StudentExam::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        $canAttempt = $exam->canStudentAttempt($user->id) && $exam->isAvailable();

        return view('student.exams.show', compact('exam', 'attempts', 'inProgress', 'canAttempt'));
    }

    public function start(Exam $exam)
    {
        $user = auth()->user();

        // Validate access
        if (!$exam->isAvailable()) {
            return back()->with('error', 'This exam is not currently available.');
        }

        if (!$exam->canStudentAttempt($user->id)) {
            return back()->with('error', 'You have reached the maximum number of attempts.');
        }

        // Check for existing in-progress exam
        $existing = StudentExam::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existing) {
            return redirect()->route('student.exams.take', ['exam' => $exam, 'studentExam' => $existing]);
        }

        DB::beginTransaction();
        try {
            // Create new attempt
            $attemptNumber = StudentExam::where('exam_id', $exam->id)
                ->where('user_id', $user->id)
                ->count() + 1;

            $studentExam = StudentExam::create([
                'user_id' => $user->id,
                'exam_id' => $exam->id,
                'attempt_number' => $attemptNumber,
                'started_at' => now(),
                'total_marks' => $exam->total_marks,
                'status' => 'in_progress',
            ]);

            DB::commit();

            return redirect()->route('student.exams.take', ['exam' => $exam, 'studentExam' => $studentExam]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to start exam: ' . $e->getMessage());
        }
    }

    public function take(Exam $exam, StudentExam $studentExam)
    {
        // Validate ownership
        if ($studentExam->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if expired
        if ($studentExam->isExpired()) {
            $this->autoSubmit($studentExam);
            return redirect()->route('student.results.show', $studentExam)
                ->with('warning', 'Exam time expired. Your answers have been auto-submitted.');
        }

        // Check if already submitted
        if ($studentExam->status !== 'in_progress') {
            return redirect()->route('student.results.show', $studentExam);
        }

        // Load questions
        $questions = $exam->questions()->with('options')->get();

        if ($exam->shuffle_questions) {
            $questions = $questions->shuffle();
        }

        // Load existing answers
        $answers = $studentExam->answers()
            ->with('selectedOption')
            ->get()
            ->keyBy('question_id');

        $remainingTime = $studentExam->remaining_time;

        return view('student.exams.take', compact('exam', 'studentExam', 'questions', 'answers', 'remainingTime'));
    }

    public function saveAnswer(Request $request)
    {
        $validated = $request->validate([
            'student_exam_id' => 'required|exists:student_exams,id',
            'question_id' => 'required|exists:questions,id',
            'selected_option_id' => 'nullable|exists:question_options,id',
            'text_answer' => 'nullable|string',
        ]);

        $studentExam = StudentExam::findOrFail($validated['student_exam_id']);

        // Validate ownership
        if ($studentExam->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if expired
        if ($studentExam->isExpired()) {
            return response()->json(['error' => 'Exam time expired'], 400);
        }

        StudentAnswer::updateOrCreate(
            [
                'student_exam_id' => $validated['student_exam_id'],
                'question_id' => $validated['question_id'],
            ],
            [
                'selected_option_id' => $validated['selected_option_id'] ?? null,
                'text_answer' => $validated['text_answer'] ?? null,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function submit(StudentExam $studentExam)
    {
        // Validate ownership
        if ($studentExam->user_id !== auth()->id()) {
            abort(403);
        }

        if ($studentExam->status !== 'in_progress') {
            return redirect()->route('student.results.show', $studentExam);
        }

        $this->gradeExam($studentExam);

        return redirect()->route('student.results.show', $studentExam)
            ->with('success', 'Exam submitted successfully!');
    }

    private function autoSubmit(StudentExam $studentExam)
    {
        $this->gradeExam($studentExam);
    }

    private function gradeExam(StudentExam $studentExam)
    {
        DB::beginTransaction();
        try {
            $score = 0;

            foreach ($studentExam->answers as $answer) {
                $question = $answer->question;

                if ($question->type === 'multiple_choice') {
                    $correctOption = $question->options()->where('is_correct', true)->first();

                    if ($correctOption && $answer->selected_option_id === $correctOption->id) {
                        $answer->update([
                            'marks_obtained' => $question->marks,
                            'is_correct' => true,
                        ]);
                        $score += $question->marks;
                    } else {
                        $answer->update([
                            'marks_obtained' => 0,
                            'is_correct' => false,
                        ]);
                    }
                }
                // Open text questions need manual grading
            }

            $studentExam->update([
                'submitted_at' => now(),
                'score' => $score,
                'status' => 'submitted',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
