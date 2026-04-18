@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Teacher Attendance</h3>

    <!-- Date Filter -->
    <div class="mt-4">
        <form action="{{ route('admin.teacher-attendance.index') }}" method="GET" class="flex items-center gap-4">
            <label for="date" class="text-gray-600 font-medium">Select Date:</label>
            <input type="date" name="date" id="date" value="{{ $date }}"
                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                onchange="this.form.submit()">
        </form>
    </div>

    <!-- Attendance Table Form -->
    <div class="mt-8">
        <form action="{{ route('admin.teacher-attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Teacher Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Remarks
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($teachers as $teacher)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $teacher->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $teacher->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center gap-4">
                                    <!-- Present -->
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="attendance[{{ $teacher->id }}][status]" value="present"
                                            class="form-radio text-green-600 border-gray-300 focus:ring-green-500"
                                            {{ ($teacher->attendance_status == 'present' || !$teacher->attendance_status) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">Present</span>
                                    </label>

                                    <!-- Absent -->
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="attendance[{{ $teacher->id }}][status]" value="absent"
                                            class="form-radio text-red-600 border-gray-300 focus:ring-red-500"
                                            {{ $teacher->attendance_status == 'absent' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">Absent</span>
                                    </label>

                                    <!-- Late -->
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="attendance[{{ $teacher->id }}][status]" value="late"
                                            class="form-radio text-yellow-500 border-gray-300 focus:ring-yellow-400"
                                            {{ $teacher->attendance_status == 'late' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">Late</span>
                                    </label>

                                    <!-- Half Day -->
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="attendance[{{ $teacher->id }}][status]" value="half_day"
                                            class="form-radio text-blue-500 border-gray-300 focus:ring-blue-400"
                                            {{ $teacher->attendance_status == 'half_day' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">Half Day</span>
                                    </label>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="text" name="attendance[{{ $teacher->id }}][remarks]"
                                    value="{{ $teacher->attendance_remarks }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                    placeholder="Optional remarks">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow-lg transition duration-150 ease-in-out">
                    Save Attendance
                </button>
            </div>
        </form>
    </div>
</div>
@endsection