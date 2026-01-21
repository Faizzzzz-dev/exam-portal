<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('lecturer.exams.show', $exam) }}" class="text-gray-600 hover:text-gray-900">
                    ← Back to Exam
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Questions for {{ $exam->title }}
                </h2>
            </div>
            <a href="{{ route('lecturer.questions.create', $exam) }}" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                Add New Question
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium">Total Questions:</span> {{ $questions->count() }}
                            </div>
                            <div>
                                <span class="font-medium">Total Marks:</span> {{ $questions->sum('marks') }}
                            </div>
                            <div>
                                <span class="font-medium">Exam Duration:</span> {{ $exam->duration_minutes }} minutes
                            </div>
                        </div>
                    </div>

                    @if($questions->count() > 0)
                        <div class="space-y-4">
                            @foreach($questions as $question)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <span class="text-sm font-medium text-gray-500">Q{{ $loop->iteration }}</span>
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    {{ $question->type === 'multiple_choice' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $question->type === 'multiple_choice' ? 'Multiple Choice' : 'Open Text' }}
                                                </span>
                                                <span class="text-sm text-gray-500">{{ $question->marks }} marks</span>
                                            </div>
                                            
                                            <p class="text-gray-800 mb-3">{{ $question->question_text }}</p>
                                            
                                            @if($question->type === 'multiple_choice' && $question->options->count() > 0)
                                                <div class="ml-4 space-y-2">
                                                    @foreach($question->options as $option)
                                                        <div class="flex items-center space-x-2">
                                                            <span class="w-6 h-6 rounded-full border-2 flex items-center justify-center text-xs
                                                                {{ $option->is_correct ? 'border-green-500 bg-green-100 text-green-700' : 'border-gray-300' }}">
                                                                {{ $option->is_correct ? '✓' : chr(65 + $option->order) }}
                                                            </span>
                                                            <span class="text-sm {{ $option->is_correct ? 'font-medium text-green-700' : 'text-gray-600' }}">
                                                                {{ $option->option_text }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center space-x-2 ml-4">
                                            <a href="{{ route('lecturer.questions.show', $question) }}" 
                                               class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                                            <a href="{{ route('lecturer.questions.edit', $question) }}" 
                                               class="text-gray-600 hover:text-gray-900 text-sm">Edit</a>
                                            <form action="{{ route('lecturer.questions.destroy', $question) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this question?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">No questions added yet.</p>
                            <a href="{{ route('lecturer.questions.create', $exam) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                                Add First Question
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>