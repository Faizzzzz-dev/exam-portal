<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('lecturer.questions.index', $question->exam) }}" class="text-gray-600 hover:text-gray-900">
                ← Back to Questions
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Question Details</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Question Header -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1 text-sm rounded-full 
                                    {{ $question->type === 'multiple_choice' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $question->type === 'multiple_choice' ? 'Multiple Choice' : 'Open Text' }}
                                </span>
                                <span class="text-sm text-gray-500">Question #{{ $question->order }}</span>
                                <span class="px-2 py-1 text-sm bg-yellow-100 text-yellow-800 rounded">{{ $question->marks }} marks</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('lecturer.questions.edit', $question) }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-black text-sm font-bold py-2 px-4 rounded">
                                    Edit Question
                                </a>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Question:</h3>
                            <p class="text-gray-700">{{ $question->question_text }}</p>
                        </div>
                    </div>

                    <!-- Multiple Choice Options -->
                    @if($question->type === 'multiple_choice' && $question->options->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Answer Options:</h3>
                            <div class="space-y-3">
                                @foreach($question->options->sortBy('order') as $option)
                                    <div class="flex items-center space-x-3 p-3 rounded-lg border 
                                        {{ $option->is_correct ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                        <span class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-sm font-medium
                                            {{ $option->is_correct ? 'border-green-500 bg-green-100 text-green-700' : 'border-gray-300' }}">
                                            {{ chr(65 + $option->order) }}
                                        </span>
                                        <span class="flex-1 text-gray-700 {{ $option->is_correct ? 'font-medium' : '' }}">
                                            {{ $option->option_text }}
                                        </span>
                                        @if($option->is_correct)
                                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Correct Answer</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Question Statistics -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Statistics:</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-gray-800">{{ $question->studentAnswers->count() }}</div>
                                <div class="text-sm text-gray-500">Total Attempts</div>
                            </div>
                            @if($question->type === 'multiple_choice')
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ $question->studentAnswers()->where('is_correct', true)->count() }}
                                    </div>
                                    <div class="text-sm text-gray-500">Correct Answers</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-red-600">
                                        {{ $question->studentAnswers()->where('is_correct', false)->count() }}
                                    </div>
                                    <div class="text-sm text-gray-500">Wrong Answers</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-blue-600">
                                        @if($question->studentAnswers->count() > 0)
                                            {{ round(($question->studentAnswers()->where('is_correct', true)->count() / $question->studentAnswers->count()) * 100, 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">Success Rate</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Exam Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Exam Information:</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-500">Exam Title:</span>
                                    <p class="font-medium text-gray-800">{{ $question->exam->title }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Subject:</span>
                                    <p class="font-medium text-gray-800">{{ $question->exam->subject->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Duration:</span>
                                    <p class="font-medium text-gray-800">{{ $question->exam->duration_minutes }} minutes</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Total Questions:</span>
                                    <p class="font-medium text-gray-800">{{ $question->exam->questions->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t">
                        <a href="{{ route('lecturer.questions.index', $question->exam) }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-black font-bold py-2 px-4 rounded">
                            Back to Questions
                        </a>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('lecturer.exams.show', $question->exam) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                View Exam
                            </a>
                            <a href="{{ route('lecturer.questions.edit', $question) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                                Edit Question
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>