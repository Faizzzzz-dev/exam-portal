<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Exams') }}</h2>
            <a href="{{ route('lecturer.exams.create') }}" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                Create New Exam
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
                    @if($exams->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Questions</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attempts</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($exams as $exam)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $exam->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $exam->subject->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $exam->duration_minutes }} min</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $exam->questions_count }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($exam->is_published)
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Draft</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $exam->student_exams_count }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('lecturer.exams.show', $exam) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                <a href="{{ route('lecturer.exams.edit', $exam) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No exams created yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>