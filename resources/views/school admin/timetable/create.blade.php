@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="timetableForm()">
    <h1 class="text-2xl font-bold mb-6">Create Timetable</h1>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.timetable.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="school_class_id">
                Select Class
            </label>
            <select name="school_class_id" id="school_class_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">-- Select Class --</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4">Subjects & Teachers</h2>
            <template x-for="(row, index) in rows" :key="index">
                <div class="flex flex-wrap -mx-2 mb-4 border-b pb-4">
                    <div class="w-full md:w-1/5 px-2 mb-2">
                        <label class="block text-gray-700 text-sm font-bold mb-1">Subject</label>
                        <select :name="`timetable[${index}][subject_id]`" x-model="row.subject_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-1/5 px-2 mb-2">
                        <label class="block text-gray-700 text-sm font-bold mb-1">Teacher</label>
                        <select :name="`timetable[${index}][teacher_id]`" x-model="row.teacher_id" @change="fetchSchedule(row.teacher_id)" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->full_label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-1/6 px-2 mb-2">
                        <label class="block text-gray-700 text-sm font-bold mb-1">Day</label>
                        <select :name="`timetable[${index}][day]`" x-model="row.day" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="repeat_all_days" value="1" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                <span class="ml-2 text-xs text-gray-600">Apply to all days (Mon-Sun)</span>
                            </label>
                        </div>
                    </div>
                    <div class="w-full md:w-1/6 px-2 mb-2">
                        <label class="block text-gray-700 text-sm font-bold mb-1">Start Time</label>
                        <input type="time" :name="`timetable[${index}][start_time]`" x-model="row.start_time" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="w-full md:w-1/6 px-2 mb-2">
                        <label class="block text-gray-700 text-sm font-bold mb-1">End Time</label>
                        <input type="time" :name="`timetable[${index}][end_time]`" x-model="row.end_time" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="w-full md:w-auto px-2 mb-2 flex items-end">
                        <button type="button" @click="removeRow(index)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Remove
                        </button>
                    </div>
                </div>
            </template>

            <button type="button" @click="addRow()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                + Add Row
            </button>
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Save Timetable
            </button>
        </div>
    </form>

    <!-- Availability Container -->
    <div id="availability-container" class="hidden mt-8 bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800" id="availability-title">Teacher Schedule</h3>
        </div>
        <div class="p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monday</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tuesday</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wednesday</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thursday</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Friday</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saturday</th>
                    </tr>
                </thead>
                <tbody id="availability-body" class="bg-white divide-y divide-gray-200">
                    <!-- Dynamic Content -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function timetableForm() {
        return {
            rows: <?php echo json_encode(old('timetable', [['subject_id' => '', 'teacher_id' => '', 'day' => 'Monday', 'start_time' => '', 'end_time' => '']])); ?>,
            errors: <?php echo json_encode($errors->messages()); ?>,
            addRow() {
                this.rows.push({
                    subject_id: '',
                    teacher_id: '',
                    day: 'Monday',
                    start_time: '',
                    end_time: ''
                });
            },
            removeRow(index) {
                this.rows.splice(index, 1);
            },
            fetchSchedule(teacherId) {
                if (!teacherId) {
                    document.getElementById('availability-container').classList.add('hidden');
                    return;
                }

                fetch(`{{ url('/admin/api/teacher-schedule') }}/${teacherId}`)
                    .then(response => response.json())
                    .then(data => {
                        const container = document.getElementById('availability-container');
                        const body = document.getElementById('availability-body');
                        const title = document.getElementById('availability-title');

                        container.classList.remove('hidden');
                        body.innerHTML = ''; // Clear previous

                        // Create a single row for the weekly view
                        let rowHtml = '<tr>';
                        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                        days.forEach(day => {
                            let cellContent = '';
                            if (data[day] && data[day].length > 0) {
                                data[day].forEach(slot => {
                                    cellContent += `<span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded mb-1 whitespace-nowrap">${slot}</span><br>`;
                                });
                            } else {
                                cellContent = '<span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded whitespace-nowrap">Full Day Free</span>';
                            }
                            rowHtml += `<td class="px-3 py-4 align-top">${cellContent}</td>`;
                        });
                        rowHtml += '</tr>';
                        body.innerHTML = rowHtml;

                        // Scroll to container
                        container.scrollIntoView({
                            behavior: 'smooth'
                        });
                    })
                    .catch(error => console.error('Error fetching schedule:', error));
            }
        }
    }
</script>
@endsection