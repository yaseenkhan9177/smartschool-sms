@extends('layouts.teacher')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Marks Entry</h2>
            @if(isset($term) && isset($class) && isset($subject))
            <p class="text-slate-500 text-sm mt-1">{{ $term->name }} &bull; {{ $class->name }} &bull; {{ $subject->name }}</p>
            @endif
        </div>
        <a href="{{ route('teacher.marks.create') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors">
            Reset Filters
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200 p-8 max-w-5xl mx-auto mb-8">
        <form action="{{ route('teacher.marks.create') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <!-- Exam Term -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Select Exam Term</label>
                <select name="term_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow bg-slate-50" required>
                    <option value="">-- Select Term --</option>
                    @foreach($terms as $t)
                    <option value="{{ $t->id }}" {{ (isset($term) && $term->id == $t->id) ? 'selected' : '' }}>
                        {{ $t->name }} ({{ \Carbon\Carbon::parse($t->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($t->end_date)->format('M d') }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Class -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Select Class</label>
                <select name="class_id" id="classSelect" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow bg-slate-50" onchange="filterSubjects()" required>
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" data-subjects='{{ json_encode($c->subjects) }}' {{ (isset($class) && $class->id == $c->id) ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Subject -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Select Subject</label>
                <select name="subject_id" id="subjectSelect" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow bg-slate-50" required>
                    <option value="">-- Select Class First --</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                    Load Students
                </button>
            </div>
        </form>
    </div>

    @if(isset($students) && count($students) > 0)
    <form action="{{ route('teacher.marks.store') }}" method="POST">
        @csrf
        <input type="hidden" name="term_id" value="{{ $term->id }}">
        <input type="hidden" name="class_id" value="{{ $class->id }}">
        <input type="hidden" name="subject_id" value="{{ $subject->id }}">

        <!-- Global Settings -->
        <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl mb-6 flex items-center gap-4">
            <div>
                <label class="block text-xs font-bold text-blue-800 uppercase tracking-widest mb-1">Total Marks</label>
                <input type="number" name="total_marks" id="totalMarks" value="{{ $total_marks ?? 100 }}" class="w-32 px-3 py-2 rounded-lg border border-blue-200 text-center font-bold text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="recalculateAllGrades()">
            </div>
            <div class="text-sm text-blue-700">
                <i class="fa-solid fa-circle-info mr-2"></i> This total will be applied to all students for this subject.
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Roll No</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Student Name</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Obtained Marks</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-24">Grade</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Remarks</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-12">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($students as $student)
                        @php
                        $existing = $existingResults[$student->id] ?? null;
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="p-4 font-mono text-sm text-slate-600">{{ $student->roll_number ?? 'N/A' }}</td>
                            <td class="p-4 font-medium text-slate-900 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs font-bold">
                                    {{ substr($student->user->name ?? $student->name ?? 'S', 0, 1) }}
                                </div>
                                {{ $student->user->name ?? $student->first_name . ' ' . $student->last_name }}
                            </td>
                            <td class="p-4">
                                <input type="number"
                                    name="marks[{{ $student->id }}]"
                                    value="{{ $existing ? $existing->obtained_marks : '' }}"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono font-bold text-slate-800 transition-shadow obtained-marks"
                                    placeholder="-"
                                    min="0"
                                    oninput="calculateGrade(this)">
                            </td>
                            <td class="p-4">
                                <span class="grade-badge px-2 py-1 rounded text-xs font-bold bg-slate-100 text-slate-500">
                                    {{ $existing ? $existing->grade : '-' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <input type="text"
                                    name="remarks[{{ $student->id }}]"
                                    value="{{ $existing ? $existing->remarks : '' }}"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                    placeholder="Optional remarks...">
                            </td>
                            <td class="p-4">
                                @if($existing)
                                <a href="{{ route('exam.result.print', ['student_id' => $student->id, 'term_id' => $term->id]) }}" target="_blank" class="text-blue-600 hover:text-blue-800" title="Print Result">
                                    <i class="fas fa-print"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-1 flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Save Results
            </button>
        </div>
    </form>
    @elseif(isset($term))
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center text-yellow-800">
        <i class="fa-solid fa-triangle-exclamation text-2xl mb-2"></i>
        <p class="font-bold">No students found</p>
        <p class="text-sm">There are no students enrolled in this class yet.</p>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        filterSubjects();
    });

    function filterSubjects() {
        const classSelect = document.getElementById('classSelect');
        const subjectSelect = document.getElementById('subjectSelect');
        const selectedOption = classSelect.options[classSelect.selectedIndex];

        // Preserve selected subject if any (from server render)
        const currentSubjectId = "{{ $subject->id ?? '' }}";

        subjectSelect.innerHTML = '<option value="">-- Select Subject --</option>';

        if (selectedOption.value) {
            try {
                const subjects = JSON.parse(selectedOption.getAttribute('data-subjects'));
                subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name + (subject.code ? ' (' + subject.code + ')' : '');
                    if (subject.id == currentSubjectId) {
                        option.selected = true;
                    }
                    subjectSelect.appendChild(option);
                });
            } catch (e) {
                console.error("Error parsing subjects", e);
            }
        }
    }

    function calculateGrade(input) {
        const row = input.closest('tr');
        const obtained = parseFloat(input.value);
        const total = parseFloat(document.getElementById('totalMarks').value);
        const badge = row.querySelector('.grade-badge');

        if (isNaN(obtained) || isNaN(total) || total === 0) {
            badge.textContent = '-';
            badge.className = 'grade-badge px-2 py-1 rounded text-xs font-bold bg-slate-100 text-slate-500';
            return;
        }

        const percentage = (obtained / total) * 100;
        let grade = 'F';
        let colorClass = 'bg-red-100 text-red-700';

        if (percentage >= 90) {
            grade = 'A+';
            colorClass = 'bg-green-100 text-green-700';
        } else if (percentage >= 80) {
            grade = 'A';
            colorClass = 'bg-green-100 text-green-700';
        } else if (percentage >= 70) {
            grade = 'B';
            colorClass = 'bg-blue-100 text-blue-700';
        } else if (percentage >= 60) {
            grade = 'C';
            colorClass = 'bg-yellow-100 text-yellow-700';
        } else if (percentage >= 50) {
            grade = 'D';
            colorClass = 'bg-orange-100 text-orange-700';
        }

        badge.textContent = grade;
        badge.className = `grade-badge px-2 py-1 rounded text-xs font-bold ${colorClass}`;
    }

    function recalculateAllGrades() {
        document.querySelectorAll('.obtained-marks').forEach(input => {
            calculateGrade(input);
        });
    }
</script>
@endsection