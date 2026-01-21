<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Subjects') }}</h2>
            <a href="{{ route('lecturer.subjects.create') }}" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                Create New Subject
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
                    @if($subjects->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classes</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exams</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($subjects as $subject)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $subject->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $subject->code }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-500">{{ Str::limit($subject->description, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-900">{{ $subject->classes_count }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-900">{{ $subject->exams_count }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('lecturer.subjects.show', $subject) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                <a href="{{ route('lecturer.subjects.edit', $subject) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">No subjects created yet.</p>
                            <a href="{{ route('lecturer.subjects.create') }}" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                                Create First Subject
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>