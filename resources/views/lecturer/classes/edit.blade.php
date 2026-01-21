
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Class') }}
            </h2>
            <a href="{{ route('lecturer.classes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-black font-bold py-2 px-4 rounded">
                Back to Classes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
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
                    <h3 class="text-lg font-semibold mb-4">Edit Class Information</h3>

                    <form action="{{ route('lecturer.classes.update', $class) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                                Class Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name', $class->name) }}" 
                                required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                                placeholder="Enter class name">
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="code" class="block text-gray-700 text-sm font-bold mb-2">
                                Class Code <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="code" 
                                id="code" 
                                value="{{ old('code', $class->code) }}" 
                                required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('code') border-red-500 @enderror"
                                placeholder="e.g., CS101">
                            @error('code')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Class code must be unique</p>
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">
                                Description
                            </label>
                            <textarea 
                                name="description" 
                                id="description" 
                                rows="4"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror"
                                placeholder="Enter class description (optional)">{{ old('description', $class->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <a href="{{ route('lecturer.classes.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-black font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                                Cancel
                            </a>
                            
                            <button 
                                type="submit" 
                                class="bg-white hover:bg-gray-100 text-black font-bold py-2 px-4 border border-gray-300 rounded shadow-md transition duration-200">
                                Update Class
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Class Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-2 text-red-600">Danger Zone</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Deleting this class will remove all associated data including student enrollments and exam assignments. This action cannot be undone.
                    </p>

                    <form action="{{ route('lecturer.classes.destroy', $class) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this class? This action cannot be undone!');">
                        @csrf
                        @method('DELETE')
                        
                        <button 
                            type="submit" 
                            class="bg-white hover:bg-gray-100 text-red font-bold py-2 px-4 border border-gray-300 rounded shadow-md transition duration-200">
                            Delete Class
                        </button>
                    </form>
                </div>
            </div>

            <!-- Class Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Class Statistics</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Total Students</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $class->students()->count() }}</p>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Subjects</p>
                            <p class="text-2xl font-bold text-green-600">{{ $class->subjects()->count() }}</p>
                        </div>
                        
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Assigned Exams</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $class->exams()->count() }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('lecturer.classes.show', $class) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View Full Class Details →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>