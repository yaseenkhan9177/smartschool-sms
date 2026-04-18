@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Update Student Record</h1>
        <p class="text-gray-500 mt-1">Update student information.</p>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $student->name) }}"
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $student->email) }}"
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $student->phone) }}"
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                        minlength="11" maxlength="11" pattern="\d{11}" title="Please enter exactly 11 digits">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Parent Phone -->
                <div>
                    <label for="parent_phone" class="block text-sm font-medium text-gray-700 mb-2">Parent/Guardian Phone</label>
                    <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}"
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                        minlength="11" maxlength="11" pattern="\d{11}" title="Please enter exactly 11 digits">
                    @error('parent_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Transport Section -->
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                    <div>
                        <label for="transport_required" class="block text-sm font-medium text-gray-700 mb-2">Transport Required?</label>
                        <select name="transport_required" id="transport_required" class="w-full px-4 py-3 rounded-xl bg-white border-transparent focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-sm" onchange="toggleTransportFee()">
                            <option value="no" {{ ($student->transport_fee <= 0 && old('transport_required') != 'yes') ? 'selected' : '' }}>No</option>
                            <option value="yes" {{ ($student->transport_fee > 0 || old('transport_required') == 'yes') ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    <div id="transport_fee_container" class="{{ ($student->transport_fee > 0 || old('transport_required') == 'yes') ? '' : 'hidden' }}">
                        <label for="transport_fee" class="block text-sm font-medium text-gray-700 mb-2">Transport Fee Amount (Monthly)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">PKR</span>
                            <input type="number" name="transport_fee" id="transport_fee" min="0"
                                class="w-full pl-12 px-4 py-3 rounded-xl bg-white border-transparent focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-sm"
                                placeholder="0" value="{{ old('transport_fee', $student->transport_fee > 0 ? $student->transport_fee : '') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Image -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                <div class="flex items-center gap-4">
                    @if($student->profile_image)
                    <img src="{{ asset($student->profile_image) }}" alt="Current Image" class="w-16 h-16 rounded-full object-cover border border-gray-200">
                    @endif
                    <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
                </div>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.students') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition-all">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all">Update Record</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleTransportFee() {
        const select = document.getElementById('transport_required');
        const container = document.getElementById('transport_fee_container');
        if (select.value === 'yes') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
            document.getElementById('transport_fee').value = '';
        }
    }
</script>
@endpush