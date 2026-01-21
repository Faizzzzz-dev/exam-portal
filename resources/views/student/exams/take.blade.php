<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $exam->title }}
            </h2>
            <div id="timer" class="text-xl font-bold text-red-600"></div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-sm text-gray-600">Subject: {{ $exam->subject->name }}</p>
                                <p class="text-sm text-gray-600">Total Marks: {{ $exam->total_marks }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Answered</p>
                                <p class="text-lg font-bold" id="progressText">{{ $answers->count() }}/{{ $questions->count() }}</p>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="progressBar" class="bg-blue-600 h-2 rounded-full" style="width: {{ ($answers->count() / $questions->count()) * 100 }}%"></div>
                        </div>
                    </div>

                    <form id="examForm" action="{{ route('student.exams.submit', $studentExam) }}" method="POST">
                        @csrf

                        @foreach($questions as $index => $question)
                            <div class="mb-8 p-4 border rounded-lg" data-question-id="{{ $question->id }}">
                                <div class="mb-4">
                                    <h3 class="font-semibold text-lg mb-2">
                                        Question {{ $index + 1 }} 
                                        <span class="text-sm text-gray-600">({{ $question->marks }} {{ $question->marks > 1 ? 'marks' : 'mark' }})</span>
                                    </h3>
                                    <p class="text-gray-700">{{ $question->question_text }}</p>
                                </div>

                                @if($question->type === 'multiple_choice')
                                    <div class="space-y-2">
                                        @foreach($question->options as $option)
                                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                                <input type="radio" 
                                                    name="question_{{ $question->id }}" 
                                                    value="{{ $option->id }}"
                                                    data-question-id="{{ $question->id }}"
                                                    class="question-answer mr-3"
                                                    {{ $answers->has($question->id) && $answers[$question->id]->selected_option_id == $option->id ? 'checked' : '' }}
                                                    onchange="saveAnswer({{ $question->id }}, {{ $option->id }}, null)">
                                                <span>{{ $option->option_text }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <textarea 
                                        name="question_{{ $question->id }}"
                                        data-question-id="{{ $question->id }}"
                                        class="question-answer w-full border rounded-lg p-3"
                                        rows="5"
                                        placeholder="Type your answer here..."
                                        onchange="saveAnswer({{ $question->id }}, null, this.value)">{{ $answers->has($question->id) ? $answers[$question->id]->text_answer : '' }}</textarea>
                                @endif
                            </div>
                        @endforeach

                        <div class="flex justify-between items-center mt-8 pt-6 border-t">
                            <button type="button" onclick="if(confirm('Are you sure you want to submit? You cannot change your answers after submission.')) { document.getElementById('examForm').submit(); }"
                                class="bg-green-500 hover:bg-green-700 text-black font-bold py-3 px-8 rounded">
                                Submit Exam
                            </button>
                            <p class="text-sm text-gray-600">Auto-save enabled - Your answers are being saved automatically</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let remainingSeconds = {{ $remainingTime * 60 }};
        const studentExamId = {{ $studentExam->id }};
        let answeredCount = {{ $answers->count() }};
        const totalQuestions = {{ $questions->count() }};

        // Timer
        function updateTimer() {
            if (remainingSeconds <= 0) {
                alert('Time is up! Your exam will be auto-submitted.');
                document.getElementById('examForm').submit();
                return;
            }

            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            document.getElementById('timer').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (remainingSeconds <= 60) {
                document.getElementById('timer').classList.add('animate-pulse');
            }

            remainingSeconds--;
        }

        setInterval(updateTimer, 1000);
        updateTimer();

        // Auto-save answers
        async function saveAnswer(questionId, optionId, textAnswer) {
            try {
                const response = await fetch('{{ route("student.exams.answer") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        student_exam_id: studentExamId,
                        question_id: questionId,
                        selected_option_id: optionId,
                        text_answer: textAnswer
                    })
                });

                if (response.ok) {
                    updateProgress();
                }
            } catch (error) {
                console.error('Failed to save answer:', error);
            }
        }

        function updateProgress() {
            const answered = document.querySelectorAll('input[type="radio"]:checked, textarea[data-question-id]').length;
            const uniqueAnswered = new Set();
            
            document.querySelectorAll('input[type="radio"]:checked').forEach(input => {
                uniqueAnswered.add(input.dataset.questionId);
            });
            
            document.querySelectorAll('textarea[data-question-id]').forEach(textarea => {
                if (textarea.value.trim() !== '') {
                    uniqueAnswered.add(textarea.dataset.questionId);
                }
            });

            const count = uniqueAnswered.size;
            const percentage = (count / totalQuestions) * 100;
            
            document.getElementById('progressBar').style.width = percentage + '%';
            document.getElementById('progressText').textContent = count + '/' + totalQuestions;
        }

        // Prevent accidental page reload
        window.addEventListener('beforeunload', function (e) {
            if (remainingSeconds > 0) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Prevent right-click and F12 (optional security feature)
        document.addEventListener('contextmenu', e => e.preventDefault());
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
                e.preventDefault();
            }
        });

        // Detect tab switching (optional)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                console.warn('Tab switched - this may be logged');
            }
        });
    </script>
</x-app-layout>