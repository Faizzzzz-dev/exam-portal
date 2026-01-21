<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Exam Results
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Result Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4">{{ $studentExam->exam->title }}</h3>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Subject</p>
                            <p class="font-medium">{{ $studentExam->exam->subject->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Submitted At</p>
                            <p class="font-medium">{{ $studentExam->submitted_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Duration Taken</p>
                            <p class="font-medium">
                                {{ $studentExam->started_at->diffInMinutes($studentExam->submitted_at) }} minutes
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Attempt Number</p>
                            <p class="font-medium">{{ $studentExam->attempt_number }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-100 rounded-lg p-6 text-center">
                        <p class="text-sm text-gray-600 mb-2">Your Score</p>
                        <p class="text-5xl font-bold {{ $studentExam->score >= $studentExam->exam->passing_marks ? 'text-green-600' : 'text-red-600' }}">
                            {{ $studentExam->percentage }}%
                        </p>
                        <p class="text-lg text-gray-600 mt-2">{{ $studentExam->score }}/{{ $studentExam->total_marks }} marks</p>
                        @if($studentExam->score >= $studentExam->exam->passing_marks)
                            <p class="text-green-600 font-medium mt-2">✓ Passed</p>
                        @else
                            <p class="text-red-600 font-medium mt-2">✗ Failed</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Answer Review -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Answer Review</h3>

                    @foreach($studentExam->exam->questions as $index => $question)
                        @php
                            $answer = $studentExam->answers->where('question_id', $question->id)->first();
                        @endphp

                        <div class="mb-6 p-4 border rounded-lg {{ $answer && $answer->is_correct ? 'bg-green-50 border-green-200' : ($answer && $answer->is_correct === false ? 'bg-red-50 border-red-200' : 'bg-gray-50') }}">
                            <div class="mb-3">
                                <h4 class="font-semibold">
                                    Question {{ $index + 1 }} 
                                    <span class="text-sm text-gray-600">({{ $question->marks }} {{ $question->marks > 1 ? 'marks' : 'mark' }})</span>
                                    @if($answer)
                                        @if($answer->is_correct === true)
                                            <span class="ml-2 text-green-600">✓ Correct</span>
                                        @elseif($answer->is_correct === false)
                                            <span class="ml-2 text-red-600">✗ Incorrect</span>
                                        @else
                                            <span class="ml-2 text-gray-600">⏳ Pending Review</span>
                                        @endif
                                    @endif
                                </h4>
                                <p class="text-gray-700 mt-1">{{ $question->question_text }}</p>
                            </div>

                            @if($question->type === 'multiple_choice')
                                <div class="space-y-2">
                                    @foreach($question->options as $option)
                                        <div class="p-2 rounded {{ $option->is_correct ? 'bg-green-100 border border-green-300' : '' }} {{ $answer && $answer->selected_option_id == $option->id && !$option->is_correct ? 'bg-red-100 border border-red-300' : '' }}">
                                            @if($option->is_correct)
                                                <span class="text-green-600 mr-2">✓</span>
                                            @endif
                                            @if($answer && $answer->selected_option_id == $option->id)
                                                <span class="mr-2">→</span>
                                            @endif
                                            {{ $option->option_text }}
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 mb-1">Your Answer:</p>
                                    <div class="p-3 bg-white border rounded">
                                        {{ $answer ? $answer->text_answer : 'No answer provided' }}
                                    </div>
                                    @if($answer && $answer->marks_obtained !== null)
                                        <p class="text-sm text-gray-600 mt-2">
                                            Marks Obtained: {{ $answer->marks_obtained }}/{{ $question->marks }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('student.dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>