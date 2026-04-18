@extends('layouts.admin')

@section('header', 'Edit Event')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Edit Event</h2>
        </div>

        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" class="p-6 space-y-6" id="editEventForm">
            @csrf
            @method('PUT')

            <!-- Event Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Title</label>
                    <input type="text" name="title" value="{{ $event->title }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Date</label>
                    <input type="datetime-local" name="event_date" value="{{ $event->event_date instanceof \DateTime ? $event->event_date->format('Y-m-d\TH:i') : \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $event->description }}</textarea>
                </div>
            </div>

            <!-- Target Audience -->
            <div class="border-t border-gray-200 pt-6">
                <label class="block text-sm font-bold text-gray-700 mb-4">Target Audience (Show to:)</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach(['student', 'teacher', 'accountant', 'admin'] as $role)
                    <label class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors {{ in_array($role, $event->target_audience ?? []) ? 'bg-indigo-50 border-indigo-200' : '' }}">
                        <input type="checkbox" name="target_audience[]" value="{{ $role }}" {{ in_array($role, $event->target_audience ?? []) ? 'checked' : '' }} class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <span class="font-medium text-gray-700 capitalize">{{ $role }}s</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.events.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700">Update Event</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('editEventForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        // Show Loading Alert
        Swal.fire({
            title: 'Updating Event...',
            text: 'Please wait while we process your request.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit Form via AJAX
        fetch(form.action, {
                method: 'POST', // POST with _method=PUT
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show Success Alert
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Event updated successfully.',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = data.redirect_url;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Something went wrong.',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again.',
                });
            });
    });
</script>
@endsection