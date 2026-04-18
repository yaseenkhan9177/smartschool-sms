@extends('layouts.teacher')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('teacher.my_classes') }}" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <span>{{ $class->name }} <span class="text-gray-400 dark:text-gray-500 font-normal text-lg">| Students</span></span>
</div>
@endsection

@section('content')
<div x-data="{ reportModal: false, selectedStudent: { id: '', name: '' } }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Student List</h3>
        <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-full text-xs font-bold">{{ $students->count() }} Students</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Student</th>
                    <th class="px-6 py-4 font-medium">Email</th>
                    <th class="px-6 py-4 font-medium">Phone</th>
                    <th class="px-6 py-4 font-medium">Parent Phone</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($students as $student)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden border border-gray-200 dark:border-gray-600">
                                @if($student->profile_image)
                                <img src="{{ asset('uploads/students/'.$student->profile_image) }}" class="w-full h-full object-cover">
                                @else
                                <i class="fa-solid fa-user text-gray-400 dark:text-gray-500"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $student->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $student->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $student->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $student->phone }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $student->parent_phone }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $student->status == 'approved' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400' }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button @click="reportModal = true; selectedStudent = { id: {{ $student->id }}, name: '{{ addslashes($student->name) }}' }" class="text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors border border-red-100">
                            <i class="fa-solid fa-flag mr-1"></i> Report
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fa-solid fa-user-slash text-2xl mb-2 opacity-50"></i>
                            <p>No students found in this class.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Report Modal -->
    <div x-show="reportModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6" x-cloak>
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="reportModal = false"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up transition-all transform border border-gray-100 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-red-500"></i> Report Student
                    </h3>
                    <button @click="reportModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>

                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">You are reporting <span class="font-bold text-gray-900 dark:text-white" x-text="selectedStudent.name"></span>. This will be sent to the admin for review.</p>

                <form action="{{ route('teacher.report.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="student_id" :value="selectedStudent.id">

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Severity</label>
                        <select name="severity" required class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-red-500 focus:ring-red-500 sm:text-sm">
                            <option value="low">Low (Minor Misconduct)</option>
                            <option value="medium">Medium (Repeated Issue)</option>
                            <option value="high">High (Severe Incident)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Reason / Incident Details</label>
                        <textarea name="reason" rows="4" required placeholder="Describe what happened..." class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-red-500 focus:ring-red-500 sm:text-sm"></textarea>
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="button" @click="reportModal = false" class="flex-1 px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 font-bold text-sm transition-colors">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 font-bold text-sm shadow-lg shadow-red-200 transition-colors">Submit Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection