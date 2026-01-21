<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\StudentExam;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();
        $classIds = $user->classes->pluck('id');

        // Get available exams
        $availableExams = Exam::whereHas('classes', function ($query) use ($classIds) {
                $query->whereIn('classes.id', $classIds);
            })
            ->where('is_published', true)
            ->with(['subject', 'classes'])
            ->get()
            ->filter(function ($exam) {
                return $exam->isAvailable();
            });

        // Get recent results
        $recentResults = StudentExam::where('user_id', $user->id)
            ->where('status', '!=', 'in_progress')
            ->with(['exam.subject'])
            ->latest()
            ->limit(5)
            ->get();

        // Get in-progress exams
        $inProgressExams = StudentExam::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->with(['exam.subject'])
            ->get()
            ->filter(function ($studentExam) {
                return !$studentExam->isExpired();
            });

        $stats = [
            'total_exams' => $availableExams->count(),
            'completed' => $recentResults->count(),
            'in_progress' => $inProgressExams->count(),
            'average_score' => $recentResults->avg('percentage') ?? 0,
        ];

        return view('student.dashboard', compact(
            'availableExams',
            'recentResults',
            'inProgressExams',
            'stats'
        ));
    }
}
