<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('lecturer.subjects.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Back to Subjects
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $subject->name }}</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Subject Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $subject->name }}</h3>
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $subject->code }}
                                </span>
                                <span class="text-sm text-gray-500">Created {{ $subject->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('lecturer.subjects.edit', $subject) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                                Edit Subject
                            </a>
                        </div>
                    </div>

                    @if($subject->description)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Description</h4>
                            <p class="text-gray-600">{{ $subject->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-gray-800">{{ $subject->classes->count() }}</div>
                            <div class="text-sm text-gray-500">Assigned Classes</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-gray-800">{{ $subject->exams->count() }}</div>
                            <div class="text-sm text-gray-500">Exams Created</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-gray-800">
                                {{ $subject->exams->sum('student_exams_count') }}
                            </div>
                            <div class="text-sm text-gray-500">Total Exam Attempts</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Classes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Assigned Classes</h3>
                    @if($subject->classes->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($subject->classes as $class)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <h4 class="font-medium text-gray-800 mb-2">{{ $class->name }}</h4>
                                    <p class="text-sm text-gray-600 mb-3">{{ $class->students_count }} students</p>
                                    <a href="{{ route('lecturer.classes.show', $class) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">View Class →</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 mb-3">No classes assigned to this subject.</p>
                            <a href="{{ route('lecturer.subjects.edit', $subject) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-black text-sm font-bold py-2 px-4 rounded">
                                Assign Classes
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Exams -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Exams</h3>
                        <a href="{{ route('lecturer.exams.create') }}?subject_id={{ $subject->id }}" 
                           class="bg-green-500 hover:bg-green-700 text-black text-sm font-bold py-2 px-4 rounded">
                            Create Exam
                        </a>
                    </div>
                    
                    @if($subject->exams->count() > 0)
                        <div class="space-y-4">
                            @foreach($subject->exams->take(5) as $exam)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-800 mb-2">{{ $exam->title }}</h4>
                                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                <span>{{ $exam->questions_count }} questions</span>
                                                <span>{{ $exam->duration_minutes }} min</span>
                                                <span>{{ $exam->total_marks }} marks</span>
                                                @if($exam->is_published)
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Draft</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('lecturer.exams.show', $exam) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                            <a href="{{ route('lecturer.questions.index', $exam) }}" 
                                               class="text-gray-600 hover:text-gray-800 text-sm">Questions</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($subject->exams->count() > 5)
                            <div class="text-center mt-4">
                                <a href="{{ route('lecturer.exams.index') }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">View All Exams →</a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 mb-3">No exams created for this subject yet.</p>
                            <a href="{{ route('lecturer.exams.create') }}?subject_id={{ $subject->id }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-black text-sm font-bold py-2 px-4 rounded">
                                Create First Exam
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>