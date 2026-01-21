<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Classes') }}
            </h2>
            <a href="{{ route('lecturer.classes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                Create New Class
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($classes->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($classes as $class)
                                <div class="border rounded-lg p-4 hover:shadow-lg transition">
                                    <h3 class="font-bold text-lg mb-2">{{ $class->name }}</h3>
                                    <p class="text-gray-600 text-sm mb-2">Code: {{ $class->code }}</p>
                                    <p class="text-gray-500 text-sm mb-4">{{ Str::limit($class->description, 100) }}</p>
                                    <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                                        <span>👥 {{ $class->students_count }} Students</span>
                                        <span>📚 {{ $class->subjects_count }} Subjects</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('lecturer.classes.show', $class) }}" class="flex-1 bg-blue-500 hover:bg-blue-700 text-black text-center py-2 px-4 rounded text-sm">
                                            View
                                        </a>
                                        <a href="{{ route('lecturer.classes.edit', $class) }}" class="flex-1 bg-gray-500 hover:bg-gray-700 text-black text-center py-2 px-4 rounded text-sm">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No classes created yet. Create your first class to get started!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
