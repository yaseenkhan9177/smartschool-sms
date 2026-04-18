@php
$layout = 'layouts.admin';
$routePrefix = 'admin';
$indexRoute = route($routePrefix . '.meetings.index');
$storeRoute = route($routePrefix . '.meetings.store');
@endphp

@extends($layout)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ $indexRoute }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Schedule Staff Meeting</h2>
                <p class="text-sm text-gray-500">Create a Zoom meeting and invite teachers.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
            @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ $storeRoute }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <!-- Topic -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Meeting Topic</label>
                        <input type="text" name="topic" required placeholder="e.g. Weekly Staff Briefing" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Date & Time -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Start Time</label>
                            <input type="datetime-local" name="start_time" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all">
                        </div>

                        <!-- Duration -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Duration (Minutes)</label>
                            <select name="duration" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all">
                                <option value="30">30 Minutes</option>
                                <option value="45">45 Minutes</option>
                                <option value="60" selected>1 Hour</option>
                                <option value="90">1 Hour 30 Min</option>
                                <option value="120">2 Hours</option>
                            </select>
                        </div>
                    </div>

                    <!-- Password (Optional) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Passcode (Optional)</label>
                        <input type="text" name="password" placeholder="Leave empty for auto-generated" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Description / Agenda</label>
                        <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all"></textarea>
                    </div>

                    <!-- Participants -->
                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 flex justify-between items-center">
                            <span>Invite Teachers</span>
                            <button type="button" onclick="toggleAllTeachers()" class="text-xs text-blue-600 hover:text-blue-800 font-bold">Select All</button>
                        </label>
                        <div class="h-48 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50">
                            @if($teachers->isEmpty())
                            <p class="text-gray-500 p-2 text-sm">No teachers found in your school.</p>
                            @else
                            @foreach($teachers as $teacher)
                            <label class="flex items-center p-2 hover:bg-white rounded transition-colors cursor-pointer border-b border-gray-100 last:border-0">
                                <input type="checkbox" name="participants[]" value="{{ $teacher->id }}" class="teacher-checkbox rounded text-blue-600 focus:ring-blue-500 h-4 w-4 border-gray-300">
                                <span class="ml-3 text-sm text-gray-700 font-medium">{{ $teacher->name }}</span>
                                <span class="ml-auto text-xs text-gray-500">{{ $teacher->subject }}</span>
                            </label>
                            @endforeach
                            @endif
                        </div>
                        <script>
                            function toggleAllTeachers() {
                                const checkboxes = document.querySelectorAll('.teacher-checkbox');
                                const isAllChecked = Array.from(checkboxes).every(cb => cb.checked);
                                checkboxes.forEach(cb => cb.checked = !isAllChecked);
                            }
                        </script>
                        <p class="text-xs text-gray-500 mt-1">Select teachers who should attend this meeting.</p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex items-center">
                        <i class="fa-solid fa-calendar-check mr-2"></i> Schedule Meeting
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection