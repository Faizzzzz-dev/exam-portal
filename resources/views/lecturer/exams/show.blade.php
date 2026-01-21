<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $exam->title }}
        </h2>
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

            <!-- Exam Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-600">Subject</p>
                            <p class="font-medium">{{ $exam->subject->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Duration</p>
                            <p class="font-medium">{{ $exam->duration_minutes }} minutes</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Marks</p>
                            <p class="font-medium">{{ $exam->total_marks }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Passing Marks</p>
                            <p class="font-medium">{{ $exam->passing_marks }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="font-medium">
                                @if($exam->is_published)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Draft</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Questions</p>
                            <p class="font-medium">{{ $exam->questions->count() }}</p>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        @if(!$exam->is_published)
                            <form action="{{ route('lecturer.exams.publish', $exam) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-black font-bold py-2 px-4 rounded">
                                    Publish Exam
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('lecturer.exams.edit', $exam) }}" class="bg-gray-500 hover:bg-gray-700 text-black font-bold py-2 px-4 rounded">
                            Edit Exam
                        </a>
                    </div>
                </div>
            </div>

            <!-- Assign to Classes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Assign to Classes</h3>
                    <form action="{{ route('lecturer.exams.assign', $exam) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                @foreach($classes as $class)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="class_ids[]" value="{{ $class->id }}"
                                            {{ $exam->classes->contains($class->id) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-blue-600">
                                        <span class="ml-2 text-sm">{{ $class->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                            Update Assignment
                        </button>
                    </form>

                    @if($exam->classes->count() > 0)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-2">Currently assigned to:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($exam->classes as $class)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ $class->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Questions Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Questions</h3>
                        <a href="{{ route('lecturer.questions.index', $exam) }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                            Manage Questions
                        </a>
                    </div>

                    @if($exam->questions->count() > 0)
                        <div class="space-y-4 mb-6">
                            @foreach($exam->questions as $index => $question)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-medium">Question {{ $index + 1 }}</h4>
                                            <p class="text-sm text-gray-600">Type: {{ ucfirst(str_replace('_', ' ', $question->type)) }} | Marks: {{ $question->marks }}</p>
                                        </div>
                                        <form action="{{ route('lecturer.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                        </form>
                                    </div>
                                    <p class="mb-2">{{ $question->question_text }}</p>

                                    @if($question->type === 'multiple_choice')
                                        <div class="ml-4 space-y-1">
                                            @foreach($question->options as $option)
                                                <div class="flex items-center">
                                                    @if($option->is_correct)
                                                        <span class="text-green-600 mr-2">✓</span>
                                                    @else
                                                        <span class="text-gray-400 mr-2">○</span>
                                                    @endif
                                                    <span class="{{ $option->is_correct ? 'font-medium text-green-600' : '' }}">
                                                        {{ $option->option_text }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 mb-4">No questions added yet.</p>
                    @endif

                    <!-- Add Question Form -->
                    <div class="border-t pt-6">
                        <h4 class="font-semibold mb-4">Add New Question</h4>
                        <form action="{{ route('lecturer.questions.store', $exam) }}" method="POST" id="questionForm">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Question Type *</label>
                                <select name="type" id="questionType" required onchange="toggleQuestionType()"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="open_text">Open Text</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Question Text *</label>
                                <textarea name="question_text" required rows="3"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Marks *</label>
                                <input type="number" name="marks" required min="1" value="1"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            </div>

                            <div id="optionsSection" class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Options</label>
                                <div id="optionsContainer" class="space-y-2">
                                    <div class="flex gap-2">
                                        <input type="text" name="options[0][text]" placeholder="Option 1" required
                                            class="flex-1 shadow border rounded py-2 px-3 text-gray-700">
                                        <input type="radio" name="correct_option" value="0" required>
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="text" name="options[1][text]" placeholder="Option 2" required
                                            class="flex-1 shadow border rounded py-2 px-3 text-gray-700">
                                        <input type="radio" name="correct_option" value="1">
                                    </div>
                                </div>
                                <button type="button" onclick="addOption()" class="mt-2 text-blue-600 text-sm">+ Add Option</button>
                            </div>

                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                                Add Question
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let optionCount = 2;

        function toggleQuestionType() {
            const type = document.getElementById('questionType').value;
            const optionsSection = document.getElementById('optionsSection');
            optionsSection.style.display = type === 'multiple_choice' ? 'block' : 'none';
        }

        function addOption() {
            const container = document.getElementById('optionsContainer');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="options[${optionCount}][text]" placeholder="Option ${optionCount + 1}" required
                    class="flex-1 shadow border rounded py-2 px-3 text-gray-700">
                <input type="radio" name="correct_option" value="${optionCount}">
            `;
            container.appendChild(div);
            optionCount++;
        }
    </script>
</x-app-layout>