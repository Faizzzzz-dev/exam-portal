<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('lecturer.classes.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Back to Classes
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $class->name }}</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Class Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $class->name }}</h3>
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $class->code }}
                                </span>
                                <span class="text-sm text-gray-500">Created {{ $class->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('lecturer.classes.edit', $class) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                                Edit Class
                            </a>
                        </div>
                    </div>

                    @if($class->description)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Description</h4>
                            <p class="text-gray-600">{{ $class->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-gray-800">{{ $class->students->count() }}</div>
                            <div class="text-sm text-gray-500">Enrolled Students</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-gray-800">{{ $class->subjects->count() }}</div>
                            <div class="text-sm text-gray-500">Assigned Subjects</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-gray-800">{{ $class->exams->count() }}</div>
                            <div class="text-sm text-gray-500">Available Exams</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrolled Students -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Enrolled Students</h3>
                        <button onclick="document.getElementById('addStudentModal').classList.remove('hidden')" 
                                class="bg-green-500 hover:bg-green-700 text-black text-sm font-bold py-2 px-4 rounded">
                            Add Students
                        </button>
                    </div>
                    
                    @if($class->students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($class->students as $student)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $student->student_id ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <form action="{{ route('lecturer.classes.remove-student', [$class, $student]) }}" 
                                                      method="POST" onsubmit="return confirm('Are you sure you want to remove this student?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 mb-3">No students enrolled in this class yet.</p>
                            <button onclick="document.getElementById('addStudentModal').classList.remove('hidden')" 
                                    class="bg-blue-500 hover:bg-blue-700 text-black text-sm font-bold py-2 px-4 rounded">
                                Add First Student
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assigned Subjects -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Assigned Subjects</h3>
                    @if($class->subjects->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($class->subjects as $subject)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <h4 class="font-medium text-gray-800 mb-2">{{ $subject->name }}</h4>
                                    <p class="text-sm text-gray-600 mb-1">Code: {{ $subject->code }}</p>
                                    <p class="text-sm text-gray-600 mb-3">{{ $subject->exams_count }} exams</p>
                                    <a href="{{ route('lecturer.subjects.show', $subject) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">View Subject →</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 mb-3">No subjects assigned to this class.</p>
                            <p class="text-sm text-gray-600">Subjects can be assigned when creating or editing subjects.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Exams -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Available Exams</h3>
                    @if($class->exams->count() > 0)
                        <div class="space-y-4">
                            @foreach($class->exams as $exam)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-800 mb-2">{{ $exam->title }}</h4>
                                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                <span>Subject: {{ $exam->subject->name }}</span>
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
                                            <a href="{{ route('lecturer.results.exam', $exam) }}" 
                                               class="text-gray-600 hover:text-gray-800 text-sm">Results</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 mb-3">No exams available for this class yet.</p>
                            <p class="text-sm text-gray-600">Exams will appear here when they are assigned to this class.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Students to Class</h3>
                
                <form action="{{ route('lecturer.classes.add-students', $class) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Select Students</label>
                        <div class="max-h-60 overflow-y-auto border rounded-lg p-3">
                            @if($availableStudents ?? false)
                                @foreach($availableStudents as $student)
                                    <label class="flex items-center mb-2">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                               class="rounded border-gray-300 text-blue-600">
                                        <span class="ml-2 text-sm">{{ $student->name }} ({{ $student->email }})</span>
                                    </label>
                                @endforeach
                            @else
                                <p class="text-gray-500 text-sm">All available students are already enrolled in this class.</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="document.getElementById('addStudentModal').classList.add('hidden')" 
                                class="bg-gray-500 hover:bg-gray-700 text-black font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        @if($availableStudents ?? false)
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                                Add Students
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>