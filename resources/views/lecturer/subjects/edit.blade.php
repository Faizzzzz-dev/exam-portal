<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('lecturer.subjects.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Back to Subjects
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Subject</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('lecturer.subjects.update', $subject) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Subject Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $subject->name) }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="code" class="block text-gray-700 text-sm font-bold mb-2">Subject Code *</label>
                            <input type="text" name="code" id="code" value="{{ old('code', $subject->code) }}" required maxlength="50"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('code') border-red-500 @enderror"
                                placeholder="e.g., MATH101">
                            @error('code')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('description', $subject->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Assign to Classes</label>
                            <div class="space-y-2">
                                @if($classes->count() > 0)
                                    @foreach($classes as $class)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="class_ids[]" value="{{ $class->id }}"
                                                {{ old('class_ids') ? (in_array($class->id, old('class_ids')) ? 'checked' : '') : ($subject->classes->contains($class->id) ? 'checked' : '') }}
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                            <span class="ml-2 text-sm text-gray-700">{{ $class->name }} ({{ $class->students_count }} students)</span>
                                        </label>
                                    @endforeach
                                @else
                                    <p class="text-sm text-gray-500">No classes available. <a href="{{ route('lecturer.classes.create') }}" class="text-blue-600 hover:text-blue-800">Create a class first</a>.</p>
                                @endif
                            </div>
                            @error('class_ids')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('lecturer.subjects.show', $subject) }}" class="bg-gray-500 hover:bg-gray-700 text-black font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <div class="flex space-x-2">
                                <form action="{{ route('lecturer.subjects.destroy', $subject) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this subject? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-black font-bold py-2 px-4 rounded">
                                        Delete
                                    </button>
                                </form>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                                    Update Subject
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>