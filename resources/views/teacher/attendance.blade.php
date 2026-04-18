@extends('layouts.teacher')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Take Attendance</h1>
                <p class="text-gray-600 mt-1">{{ $class->name }}</p>
            </div>
            <a href="{{ route('teacher.my_classes') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Classes
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form id="attendance-form" action="{{ route('teacher.attendance.store', $class->id) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" value="{{ $today }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="text-sm text-gray-500">
                    <i class="fa-solid fa-users mr-1"></i> {{ $students->count() }} Students
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4 text-center">Present</th>
                        <th class="px-6 py-4 text-center">Absent</th>
                        <th class="px-6 py-4 text-center">Late</th>
                        <th class="px-6 py-4 text-center">Excused</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border border-gray-200">
                                    @if($student->profile_image)
                                        <img src="{{ asset('uploads/students/'.$student->profile_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-solid fa-user text-gray-400"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <input type="radio" name="attendance[{{ $student->id }}]" value="present" 
                                   {{ (isset($existingAttendance[$student->id]) && $existingAttendance[$student->id] == 'present') || !isset($existingAttendance[$student->id]) ? 'checked' : '' }}
                                   class="w-5 h-5 text-green-600 focus:ring-green-500" required>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <input type="radio" name="attendance[{{ $student->id }}]" value="absent"
                                   {{ isset($existingAttendance[$student->id]) && $existingAttendance[$student->id] == 'absent' ? 'checked' : '' }}
                                   class="w-5 h-5 text-red-600 focus:ring-red-500" required>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <input type="radio" name="attendance[{{ $student->id }}]" value="late"
                                   {{ isset($existingAttendance[$student->id]) && $existingAttendance[$student->id] == 'late' ? 'checked' : '' }}
                                   class="w-5 h-5 text-yellow-600 focus:ring-yellow-500" required>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <input type="radio" name="attendance[{{ $student->id }}]" value="excused"
                                   {{ isset($existingAttendance[$student->id]) && $existingAttendance[$student->id] == 'excused' ? 'checked' : '' }}
                                   class="w-5 h-5 text-blue-600 focus:ring-blue-500" required>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3 text-gray-300 text-2xl">
                                    <i class="fa-solid fa-user-graduate"></i>
                                </div>
                                <p class="font-medium">No students in this class</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->count() > 0)
        <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('teacher.my_classes') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
                <i class="fa-solid fa-check mr-2"></i> Save Attendance
            </button>
        </div>
        @endif
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('attendance-form');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            // Check if Offline
            if (!navigator.onLine) {
                e.preventDefault(); // Stop normal submission
                
                // Get Form Data
                const classId = "{{ $class->id }}";
                const dateVal = form.querySelector('input[name="date"]').value;
                const studentInputs = form.querySelectorAll('input[type="radio"]:checked');
                
                if (!smsDbPromise) {
                    Swal.fire('Error', 'Offline storage not initialized.', 'error');
                    return;
                }
                
                try {
                    const db = await smsDbPromise;
                    
                    // Loop over each selected attendance and save to IDB
                    for (const input of studentInputs) {
                        // Extract student ID from name attribution (e.g., "attendance[5]")
                        const studentIdMatch = input.name.match(/\[(\d+)\]/);
                        if (studentIdMatch && studentIdMatch[1]) {
                            const studentId = parseInt(studentIdMatch[1]);
                            const status = input.value;
                            
                            const record = {
                                id: crypto.randomUUID(), // Guarantee uniqueness
                                student_id: studentId,
                                school_class_id: classId,
                                date: dateVal,
                                status: status,
                                synced: false,
                                created_at: new Date().toISOString()
                            };
                            
                            await db.put('attendance', record);
                        }
                    }
                    
                    // Trigger Background Sync if supported
                    if ('serviceWorker' in navigator && 'SyncManager' in window) {
                        const registration = await navigator.serviceWorker.ready;
                        await registration.sync.register('sync-attendance');
                        console.log("Registered background sync");
                    }
                    
                    Swal.fire({
                        icon: 'info',
                        title: 'Saved Offline',
                        text: 'Attendance recorded locally. It will automatically sync when you reconnect.',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => {
                        window.location.href = "{{ route('teacher.my_classes') }}";
                    });
                    
                } catch (error) {
                    console.error("Failed to save offline attendance:", error);
                    Swal.fire('Error', 'Could not save attendance offline.', 'error');
                }
            }
        });
    }
});
</script>
@endsection
