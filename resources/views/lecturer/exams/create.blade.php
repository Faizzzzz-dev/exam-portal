<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create New Exam') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('lecturer.exams.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Exam Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="subject_id" class="block text-gray-700 text-sm font-bold mb-2">Subject *</label>
                            <select name="subject_id" id="subject_id" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('subject_id') border-red-500 @enderror">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="duration_minutes" class="block text-gray-700 text-sm font-bold mb-2">Duration (minutes) *</label>
                                <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', 15) }}" required min="1"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('duration_minutes') border-red-500 @enderror">
                                @error('duration_minutes')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="passing_marks" class="block text-gray-700 text-sm font-bold mb-2">Passing Marks *</label>
                                <input type="number" name="passing_marks" id="passing_marks" value="{{ old('passing_marks', 50) }}" required min="0"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('passing_marks') border-red-500 @enderror">
                                @error('passing_marks')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_time" class="block text-gray-700 text-sm font-bold mb-2">Start Time</label>
                                <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                            </div>

                            <div>
                                <label for="end_time" class="block text-gray-700 text-sm font-bold mb-2">End Time</label>
                                <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="max_attempts" class="block text-gray-700 text-sm font-bold mb-2">Max Attempts *</label>
                            <input type="number" name="max_attempts" id="max_attempts" value="{{ old('max_attempts', 1) }}" required min="1"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('max_attempts') border-red-500 @enderror">
                            @error('max_attempts')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="shuffle_questions" value="1" {{ old('shuffle_questions') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <span class="ml-2 text-sm text-gray-700">Shuffle Questions</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('lecturer.exams.index') }}" class="bg-gray-500 hover:bg-gray-700 text-black font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-white hover:bg-gray-100 text-black font-bold py-2 px-4 border border-gray-300 rounded shadow-md transition duration-200">
                                Create Exam
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>