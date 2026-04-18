@extends('layouts.student')

@section('header')
Personal Calendar
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Calendar Card -->
    <div class="lg:col-span-3">
        <div class="glass-card rounded-3xl p-6">
            <div id='calendar' data-events="{{ json_encode($reminders) }}"></div>
        </div>
    </div>

    <!-- Quick Reminder Card -->
    <div>
        <div class="glass-card rounded-3xl p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Add Reminder</h3>
            <form action="{{ route('student.reminders.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                    <input type="text" name="title" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date & Time</label>
                    <input type="datetime-local" name="reminder_date" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                </div>
                <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">Add Reminder</button>
            </form>
        </div>

        <!-- Upcoming Reminders List -->
        <div class="glass-card rounded-3xl p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Your Reminders</h3>
            @if(count($reminders) > 0)
            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                @foreach($reminders as $reminder)
                <div class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-gray-700 relative group">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm">{{ $reminder['title'] }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($reminder['start'])->format('M d, h:i A') }}
                            </p>
                            @if($reminder['description'])
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $reminder['description'] }}</p>
                            @endif
                        </div>
                        <form action="{{ route('student.reminders.destroy', $reminder['id']) }}" method="POST" onsubmit="return confirm('Delete this reminder?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No reminders found.</p>
            @endif
        </div>
    </div>
</div>

<!-- FullCalendar CSS/JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var eventsData = JSON.parse(calendarEl.getAttribute('data-events')); // Parse from attribute
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: eventsData,
            themeSystem: 'standard',
            height: 'auto',
            editable: true, // Allow drag drop if we implemented update logic
            eventClick: function(info) {
                alert('Event: ' + info.event.title + '\nDescription: ' + (info.event.extendedProps.description || 'N/A'));
            }
        });
        calendar.render();
    });
</script>

<style>
    /* FullCalendar Customization */
    :root {
        --fc-border-color: rgba(229, 231, 235, 1);
        --fc-button-bg-color: #3b82f6;
        --fc-button-border-color: #3b82f6;
        --fc-button-hover-bg-color: #2563eb;
        --fc-button-hover-border-color: #2563eb;
        --fc-today-bg-color: rgba(59, 130, 246, 0.1);
    }

    .dark {
        --fc-border-color: rgba(71, 85, 105, 1);
        --fc-page-bg-color: #1e293b;
        --fc-neutral-bg-color: #1e293b;
        --fc-list-event-hover-bg-color: #334155;
    }

    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: inherit;
    }

    .fc .fc-col-header-cell-cushion {
        padding: 8px;
        color: inherit;
    }

    .fc-theme-standard td,
    .fc-theme-standard th {
        border-color: var(--fc-border-color);
    }
</style>
@endsection