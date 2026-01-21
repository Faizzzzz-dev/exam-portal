<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('lecturer.questions.index', $question->exam) }}" class="text-gray-600 hover:text-gray-900">
                ← Back to Questions
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Question</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('lecturer.questions.update', $question) }}" method="POST" id="questionForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Question Type *</label>
                            <select name="type" id="type" required onchange="toggleQuestionType()"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('type') border-red-500 @enderror">
                                <option value="">Select Question Type</option>
                                <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>
                                    Multiple Choice
                                </option>
                                <option value="open_text" {{ $question->type == 'open_text' ? 'selected' : '' }}>
                                    Open Text
                                </option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="question_text" class="block text-gray-700 text-sm font-bold mb-2">Question Text *</label>
                            <textarea name="question_text" id="question_text" rows="4" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('question_text') border-red-500 @enderror">{{ $question->question_text }}</textarea>
                            @error('question_text')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="marks" class="block text-gray-700 text-sm font-bold mb-2">Marks *</label>
                            <input type="number" name="marks" id="marks" value="{{ $question->marks }}" required min="1"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('marks') border-red-500 @enderror">
                            @error('marks')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Multiple Choice Options -->
                        <div id="multipleChoiceOptions" class="hidden">
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-3">
                                    <label class="block text-gray-700 text-sm font-bold">Answer Options *</label>
                                    <button type="button" onclick="addOption()" class="bg-green-500 hover:bg-green-700 text-black text-sm font-bold py-1 px-3 rounded">
                                        + Add Option
                                    </button>
                                </div>
                                <div id="optionsContainer" class="space-y-3">
                                    @foreach($question->options as $index => $option)
                                        <div class="option-item flex items-center space-x-3">
                                            <span class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-sm font-medium
                                                {{ $option->is_correct ? 'border-green-500 bg-green-100 text-green-700' : 'border-gray-300' }}">
                                                {{ chr(65 + $option->order) }}
                                            </span>
                                            <input type="text" name="options[{{ $index }}][text]" value="{{ $option->option_text }}" required
                                                class="flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700">
                                            <input type="radio" name="correct_option" value="{{ $index }}" 
                                                {{ $option->is_correct ? 'checked' : '' }} required
                                                class="w-4 h-4 text-blue-600">
                                            <span class="text-sm text-gray-500">Correct</span>
                                            @if($question->options->count() > 2)
                                                <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @error('options')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('lecturer.questions.index', $question->exam) }}" class="bg-gray-500 hover:bg-gray-700 text-black font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                                Update Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let optionCount = {{ $question->options->count() }};

        function toggleQuestionType() {
            const type = document.getElementById('type').value;
            const optionsDiv = document.getElementById('multipleChoiceOptions');
            
            if (type === 'multiple_choice') {
                optionsDiv.classList.remove('hidden');
            } else {
                optionsDiv.classList.add('hidden');
            }
        }

        function addOption() {
            const container = document.getElementById('optionsContainer');
            const optionDiv = document.createElement('div');
            optionDiv.className = 'option-item flex items-center space-x-3';
            
            const letter = String.fromCharCode(65 + optionCount);
            optionDiv.innerHTML = '<span class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center text-sm font-medium">' + letter + '</span>' +
                '<input type="text" name="options[' + optionCount + '][text]" placeholder="Option ' + letter + '" required class="flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700">' +
                '<input type="radio" name="correct_option" value="' + optionCount + '" required class="w-4 h-4 text-blue-600">' +
                '<span class="text-sm text-gray-500">Correct</span>' +
                '<button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700 text-sm">Remove</button>';
            
            container.appendChild(optionDiv);
            optionCount++;
        }

        function removeOption(button) {
            const optionDiv = button.parentElement;
            optionDiv.remove();
            updateOptionLetters();
        }

        function updateOptionLetters() {
            const options = document.querySelectorAll('.option-item');
            options.forEach(function(option, index) {
                const letter = String.fromCharCode(65 + index);
                const span = option.querySelector('span');
                const input = option.querySelector('input[type="text"]');
                const radio = option.querySelector('input[type="radio"]');
                
                span.textContent = letter;
                span.className = 'w-8 h-8 rounded-full border-2 flex items-center justify-center text-sm font-medium border-gray-300';
                input.placeholder = 'Option ' + letter;
                input.name = 'options[' + index + '][text]';
                radio.value = index;
                radio.checked = false;
            });
            optionCount = options.length;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleQuestionType();
        });
    </script>
</x-app-layout>