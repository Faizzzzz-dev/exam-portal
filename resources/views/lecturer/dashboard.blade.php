<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lecturer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Total Classes</div>
                    <div class="text-3xl font-bold">{{ auth()->user()->createdClasses()->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Total Subjects</div>
                    <div class="text-3xl font-bold">{{ auth()->user()->createdSubjects()->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Total Exams</div>
                    <div class="text-3xl font-bold">{{ auth()->user()->createdExams()->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Published Exams</div>
                    <div class="text-3xl font-bold">{{ auth()->user()->createdExams()->where('is_published', true)->count() }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('lecturer.classes.create') }}" class="block p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 text-center">
                        <div class="text-2xl mb-2">➕</div>
                        <div class="font-medium">Create New Class</div>
                    </a>
                    <a href="{{ route('lecturer.subjects.create') }}" class="block p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 text-center">
                        <div class="text-2xl mb-2">📚</div>
                        <div class="font-medium">Create New Subject</div>
                    </a>
                    <a href="{{ route('lecturer.exams.create') }}" class="block p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 text-center">
                        <div class="text-2xl mb-2">📝</div>
                        <div class="font-medium">Create New Exam</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
