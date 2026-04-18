@extends(request()->routeIs('accountant.*') ? 'layouts.accountant' : 'layouts.admin')

@section('header')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Create Schedule</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add multiple exams for a specific class.</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-5xl mx-auto" x-data="scheduleForm()">

    <form action="{{ route($routePrefix . '.exam-schedules.store') }}" method="POST">
        @csrf

        <!-- Top Section: Class & Term -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">1. Select Class & Term</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Exam Term -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Exam Term</label>
                    <select name="term_id" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-purple-500 focus:ring-purple-500">
                        @foreach($terms as $term)
                        <option value="{{ $term->id }}">{{ $term->name }} ({{ $term->is_active ? 'Active' : 'Inactive' }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Class Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Class</label>
                    <select name="class_id" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-purple-500 focus:ring-purple-500">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Section Input (Optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section (Optional)</label>
                    <input type="text" name="section" placeholder="e.g. A, Red, Boys" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-purple-500 focus:ring-purple-500">
                </div>
            </div>
        </div>

        <!-- Dynamic Rows Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">2. Schedule Subjects</h3>
                <button type="button" @click="addRow()" class="px-4 py-2 bg-blue-50 text-blue-600 text-sm font-bold rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fa-solid fa-plus mr-1"></i> Add Subject
                </button>
            </div>

            <!-- Error Display -->
            @if($errors->has('conflicts'))
            <div class="p-4 bg-red-50 border-b border-red-100">
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach($errors->get('conflicts')[0] as $conflict)
                    <li>{{ $conflict }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400 min-w-[1000px]">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase font-bold text-gray-500 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3 w-48">Subject / Type</th>
                            <th class="px-4 py-3 w-40">Date</th>
                            <th class="px-4 py-3 w-32">Time</th>
                            <th class="px-4 py-3 w-32">Room / Supervisor</th>
                            <th class="px-4 py-3 w-24">Marks</th>
                            <th class="px-2 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <template x-for="(row, index) in rows" :key="index">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-3 space-y-2">
                                    <select name="subjects[]" required class="w-full rounded-lg border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-3 py-2 text-sm focus:border-purple-500 focus:ring-purple-500">
                                        <option value="">Subject</option>
                                        @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="paper_types[]" class="w-full rounded-lg border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-3 py-2 text-xs focus:border-purple-500 focus:ring-purple-500">
                                        <option value="Theory">Theory</option>
                                        <option value="Practical">Practical</option>
                                        <option value="Viva">Viva</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3 align-top">
                                    <input type="date" name="dates[]" required class="w-full rounded-lg border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-3 py-2 text-sm focus:border-purple-500 focus:ring-purple-500">
                                </td>
                                <td class="px-4 py-3 space-y-2">
                                    <div class="flex items-center gap-1">
                                        <span class="text-xs text-gray-400 w-8">Start</span>
                                        <input type="time" name="start_times[]" required class="flex-1 rounded-lg border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-2 py-1 text-sm focus:border-purple-500 focus:ring-purple-500">
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-xs text-gray-400 w-8">End</span>
                                        <input type="time" name="end_times[]" required class="flex-1 rounded-lg border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-2 py-1 text-sm focus:border-purple-500 focus:ring-purple-500">
                                    </div>
                                </td>
                                <td class="px-4 py-3 space-y-2">
                                    <input type="text" name="rooms[]" placeholder="Room No" class="w-full rounded-lg border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-3 py-2 text-sm focus:border-purple-500 focus:ring-purple-500">
                                    <select name="supervisors[]" class="w-full rounded-lg border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-3 py-2 text-xs focus:border-purple-500 focus:ring-purple-500">
                                        <option value="">Supervisor (Optional)</option>
                                        @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3 space-y-2">
                                    <div class="flex flex-col">
                                        <label class="text-[10px] text-gray-400">Total</label>
                                        <input type="number" name="total_marks[]" value="100" class="w-full rounded-lg border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-2 py-1 text-sm focus:border-purple-500 focus:ring-purple-500">
                                    </div>
                                    <div class="flex flex-col">
                                        <label class="text-[10px] text-gray-400">Pass</label>
                                        <input type="number" name="passing_marks[]" value="33" class="w-full rounded-lg border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-2 py-1 text-sm focus:border-purple-500 focus:ring-purple-500">
                                    </div>
                                </td>
                                <td class="px-2 py-3 text-center align-middle">
                                    <button type="button" @click="removeRow(index)" class="text-red-400 hover:text-red-600 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" x-show="rows.length > 1">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="p-6 bg-gray-50 dark:bg-gray-700/30 flex justify-center">
                <button type="button" @click="addRow()" class="text-blue-600 font-bold text-sm hover:underline flex items-center">
                    <i class="fa-solid fa-plus-circle mr-2"></i> Add Another Subject
                </button>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 pb-12">
            <a href="{{ route($routePrefix . '.exam-schedules.index') }}" class="px-6 py-3 bg-white text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors shadow-sm border border-gray-200">Cancel</a>
            <button type="submit" class="px-8 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition-colors shadow-lg shadow-purple-600/20 flex items-center">
                <i class="fa-solid fa-save mr-2"></i> Save Schedule
            </button>
        </div>

    </form>
</div>

<script>
    function scheduleForm() {
        return {
            rows: [{
                    id: 1
                } // Initial row
            ],
            addRow() {
                this.rows.push({
                    id: Date.now()
                });
            },
            removeRow(index) {
                this.rows.splice(index, 1);
            }
        }
    }
</script>
@endsection