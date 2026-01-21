
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Available Exams</div>
                    <div class="text-3xl font-bold">{{ $stats['total_exams'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Completed</div>
                    <div class="text-3xl font-bold">{{ $stats['completed'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">In Progress</div>
                    <div class="text-3xl font-bold">{{ $stats['in_progress'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Average Score</div>
                    <div class="text-3xl font-bold">{{ number_format($stats['average_score'], 1) }}%</div>
                </div>
            </div>

            <!-- In Progress Exams -->
            @if($inProgressExams->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Resume Exam</h3>
                        @foreach($inProgressExams as $studentExam)
                            <div class="border rounded-lg p-4 mb-4 bg-yellow-50">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="font-medium">{{ $studentExam->exam->title }}</h4>
                                        <p class="text-sm text-gray-600">{{ $studentExam->exam->subject->name }}</p>
                                        <p class="text-sm text-red-600 mt-1">⏱️ Time remaining: {{ $studentExam->remaining_time }} minutes</p>
                                    </div>
                                    <a href="{{ route('student.exams.take', ['exam' => $studentExam->exam, 'studentExam' => $studentExam]) }}" 
                                        class="bg-orange-500 hover:bg-orange-700 text-black font-bold py-2 px-4 rounded">
                                        Resume
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Available Exams -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Available Exams</h3>
                    <?php
                    // dd($availableExams,$availableExams->count() > 0);
                    ?>
                    @if($availableExams->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($availableExams as $exam)
                                <div class="border rounded-lg p-4 hover:shadow-lg transition">
                                    <h4 class="font-bold text-lg mb-2">{{ $exam->title }}</h4>
                                    <p class="text-sm text-gray-600 mb-2">{{ $exam->subject->name }}</p>
                                    <div class="text-sm text-gray-500 space-y-1 mb-4">
                                        <p>⏱️ Duration: {{ $exam->duration_minutes }} minutes</p>
                                        <p>📝 Questions: {{ $exam->questions->count() }}</p>
                                        <p>🎯 Total Marks: {{ $exam->total_marks }}</p>
                                    </div>
                                    <a href="{{ route('student.exams.show', $exam) }}" 
                                        class="block text-center bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                                        View Details
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No exams available at the moment.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Results -->
            @if($recentResults->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Recent Results</h3>
                        <div class="space-y-3">
                            @foreach($recentResults as $result)
                                <div class="border rounded-lg p-4 flex justify-between items-center">
                                    <div>
                                        <h4 class="font-medium">{{ $result->exam->title }}</h4>
                                        <p class="text-sm text-gray-600">{{ $result->exam->subject->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $result->submitted_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold {{ $result->score >= $result->exam->passing_marks ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $result->percentage }}%
                                        </div>
                                        <div class="text-sm text-gray-600">{{ $result->score }}/{{ $result->total_marks }}</div>
                                        <a href="{{ route('student.results.show', $result) }}" class="text-blue-600 text-sm hover:underline">View Details</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

