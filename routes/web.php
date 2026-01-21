<?php


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Lecturer\ClassController;
use App\Http\Controllers\Lecturer\SubjectController;
use App\Http\Controllers\Lecturer\ExamController as LecturerExamController;
use App\Http\Controllers\Lecturer\QuestionController;
use App\Http\Controllers\Lecturer\ResultController as LecturerResultController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ExamController as StudentExamController;
use App\Http\Controllers\Student\ResultController as StudentResultController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isLecturer()) {
            return redirect()->route('lecturer.dashboard');
        }

        if (auth()->user()->isStudent()) {
            return redirect()->route('student.dashboard');
        }
    }

    return redirect()->route('login');
});


// Lecturer Routes
Route::middleware(['auth', 'lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('lecturer.dashboard');
    })->name('dashboard');

    // Class Management
    Route::resource('classes', ClassController::class);
    Route::post('classes/{class}/students', [ClassController::class, 'addStudents'])->name('classes.add-students');
    Route::delete('classes/{class}/students/{student}', [ClassController::class, 'removeStudent'])->name('classes.remove-student');

    // Subject Management
    Route::resource('subjects', SubjectController::class);

    // Exam Management
    Route::resource('exams', LecturerExamController::class);
    Route::post('exams/{exam}/publish', [LecturerExamController::class, 'publish'])->name('exams.publish');
    Route::post('exams/{exam}/assign', [LecturerExamController::class, 'assign'])->name('exams.assign');

    // Question Management
    Route::get('exams/{exam}/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('exams/{exam}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('exams/{exam}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('questions/{question}', [QuestionController::class, 'show'])->name('questions.show');
    Route::get('questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // Results
    Route::get('results', [LecturerResultController::class, 'index'])->name('results.index');
    Route::get('results/exam/{exam}', [LecturerResultController::class, 'examResults'])->name('results.exam');
    Route::get('results/student-exam/{studentExam}', [LecturerResultController::class, 'studentExamDetail'])->name('results.detail');
    Route::post('results/grade/{answer}', [LecturerResultController::class, 'gradeAnswer'])->name('results.grade');
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    // Exam Taking
    Route::get('exams', [StudentExamController::class, 'index'])->name('exams.index');
    Route::get('exams/{exam}', [StudentExamController::class, 'show'])->name('exams.show');
    Route::post('exams/{exam}/start', [StudentExamController::class, 'start'])->name('exams.start');
    Route::get('exams/{exam}/take/{studentExam}', [StudentExamController::class, 'take'])->name('exams.take');
    Route::post('exams/answer', [StudentExamController::class, 'saveAnswer'])->name('exams.answer');
    Route::post('exams/{studentExam}/submit', [StudentExamController::class, 'submit'])->name('exams.submit');

    // Results
    Route::get('results', [StudentResultController::class, 'index'])->name('results.index');
    Route::get('results/{studentExam}', [StudentResultController::class, 'show'])->name('results.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
