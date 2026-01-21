<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions for an exam.
     */
    public function index(Exam $exam)
    {
        $questions = $exam->questions()->with('options')->orderBy('order')->get();
        
        return view('lecturer.questions.index', compact('exam', 'questions'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create(Exam $exam)
    {
        return view('lecturer.questions.create', compact('exam'));
    }

    /**
     * Display the specified question.
     */
    public function show(Question $question)
    {
        $question->load(['options', 'exam']);
        
        return view('lecturer.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(Question $question)
    {
        $question->load('options');
        
        return view('lecturer.questions.edit', compact('question'));
    }
    public function store(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'type' => 'required|in:multiple_choice,open_text',
            'question_text' => 'required|string',
            'marks' => 'required|integer|min:1',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'options.*.text' => 'required_if:type,multiple_choice|string',
            'options.*.is_correct' => 'nullable|boolean',
            'correct_option' => 'required_if:type,multiple_choice|integer',
        ]);

        DB::beginTransaction();
        try {
            $order = $exam->questions()->max('order') + 1;

            $question = $exam->questions()->create([
                'type' => $validated['type'],
                'question_text' => $validated['question_text'],
                'marks' => $validated['marks'],
                'order' => $order,
            ]);

            if ($validated['type'] === 'multiple_choice' && isset($validated['options'])) {
                foreach ($validated['options'] as $index => $option) {
                    $question->options()->create([
                        'option_text' => $option['text'],
                        'is_correct' => $index == $validated['correct_option'],
                        'order' => $index,
                    ]);
                }
            }

            // Update exam total marks
            $exam->update([
                'total_marks' => $exam->questions()->sum('marks')
            ]);

            DB::commit();

            return back()->with('success', 'Question added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add question: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'marks' => 'required|integer|min:1',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'options.*.text' => 'required_if:type,multiple_choice|string',
            'correct_option' => 'required_if:type,multiple_choice|integer',
        ]);

        DB::beginTransaction();
        try {
            $question->update([
                'question_text' => $validated['question_text'],
                'marks' => $validated['marks'],
            ]);

            if ($question->type === 'multiple_choice' && isset($validated['options'])) {
                $question->options()->delete();

                foreach ($validated['options'] as $index => $option) {
                    $question->options()->create([
                        'option_text' => $option['text'],
                        'is_correct' => $index == $validated['correct_option'],
                        'order' => $index,
                    ]);
                }
            }

            // Update exam total marks
            $question->exam->update([
                'total_marks' => $question->exam->questions()->sum('marks')
            ]);

            DB::commit();

            return back()->with('success', 'Question updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update question: ' . $e->getMessage());
        }
    }

    public function destroy(Question $question)
    {
        $exam = $question->exam;
        $question->delete();

        // Update exam total marks
        $exam->update([
            'total_marks' => $exam->questions()->sum('marks')
        ]);

        return back()->with('success', 'Question deleted successfully.');
    }
}
