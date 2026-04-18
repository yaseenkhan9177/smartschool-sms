@extends('layouts.accountant')

@section('content')
<div x-data="{ reportModal: false, passwordModal: false, selectedStudent: { id: '', name: '' } }" class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Students</h1>
            <p class="text-gray-500 text-sm mt-1">View all students and their statuses</p>
        </div>
        <div>
            <a href="{{ route('accountant.students.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add Student
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium">Student</th>
                        <th class="px-6 py-4 font-medium">Contact</th>
                        <th class="px-6 py-4 font-medium">Class</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($student->profile_image)
                                <img src="{{ asset('uploads/students/' . $student->profile_image) }}" alt="{{ $student->name }}" class="w-10 h-10 rounded-full object-cover border border-purple-100">
                                @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center text-purple-600 font-bold border border-purple-100">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $student->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900">{{ $student->email }}</p>
                            <p class="text-xs text-gray-500">{{ $student->phone ?? 'No Phone' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $student->schoolClass->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($student->status == 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                Approved
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-100">
                                Pending
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('accountant.students.show', $student->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors hover:underline mr-2" title="View Profile & History">
                                    Profile
                                </a>
                                <a href="{{ route('accountant.students.fee_card', $student->id) }}" target="_blank" class="text-amber-600 hover:text-amber-800 text-sm font-medium transition-colors hover:underline mr-2" title="View Fee Card">
                                    Fee Card
                                </a>
                                <a href="{{ route('accountant.fees.invoice.consolidated', $student->id) }}" target="_blank" class="text-purple-600 hover:text-purple-800 text-sm font-medium transition-colors hover:underline" title="View Consolidated Invoice">
                                    Invoice
                                </a>
                                <!-- Add Reset Password Button here -->
                                <button @click="passwordModal = true; selectedStudent = { id: {{ $student->id }}, name: '{{ addslashes($student->name) }}' }" class="text-xs font-bold text-amber-600 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition-colors border border-amber-100 mr-2" title="Reset Password">
                                    <i class="fa-solid fa-key mr-1"></i> Reset Pass
                                </button>

                                <button @click="reportModal = true; selectedStudent = { id: {{ $student->id }}, name: '{{ addslashes($student->name) }}' }" class="text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors border border-red-100" title="Report Student">
                                    <i class="fa-solid fa-flag mr-1"></i> Report
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <p>No students found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $students->links() }}
        </div>
        @endif
    </div>

    <!-- Report Modal -->
    <div x-show="reportModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6" x-cloak>
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="reportModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up transition-all transform border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-red-500"></i> Report Student
                    </h3>
                    <button @click="reportModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>

                <p class="text-sm text-gray-500 mb-4">You are reporting <span class="font-bold text-gray-900" x-text="selectedStudent.name"></span>. This will be sent to the admin for review.</p>

                <form action="{{ route('accountant.reports.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="student_id" :value="selectedStudent.id">

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Severity</label>
                        <select name="severity" required class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:border-red-500 focus:ring-red-500 sm:text-sm">
                            <option value="low">Low (Minor Misconduct)</option>
                            <option value="medium">Medium (Repeated Issue)</option>
                            <option value="high">High (Severe Incident)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Reason / Incident Details</label>
                        <textarea name="reason" rows="4" required placeholder="Describe what happened..." class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:border-red-500 focus:ring-red-500 sm:text-sm"></textarea>
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="button" @click="reportModal = false" class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-bold text-sm transition-colors">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 font-bold text-sm shadow-lg shadow-red-200 transition-colors">Submit Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Reset Modal -->
    <div x-show="passwordModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6" x-cloak>
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="passwordModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up transition-all transform border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-key text-amber-500"></i> Reset Password
                    </h3>
                    <button @click="passwordModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>

                <p class="text-sm text-gray-500 mb-4">You are resetting the password for <span class="font-bold text-gray-900" x-text="selectedStudent.name"></span>.</p>

                <form :action="`{{ url('accountant/students') }}/${selectedStudent.id}/reset-password`" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">New Password</label>
                        <input type="password" name="password" required minlength="6" placeholder="Enter new password" class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="button" @click="passwordModal = false" class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-bold text-sm transition-colors">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-amber-600 text-white rounded-xl hover:bg-amber-700 font-bold text-sm shadow-lg shadow-amber-200 transition-colors">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection