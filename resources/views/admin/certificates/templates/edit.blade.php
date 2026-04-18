@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-2 mb-6 text-gray-500 text-sm">
            <a href="{{ route('admin.certificates.templates.index') }}" class="hover:text-blue-600">Templates</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Edit Template</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Section -->
            <div class="lg:col-span-2">
                <form action="{{ route('admin.certificates.templates.update', $template->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Type</label>
                        <select name="type_id" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ $template->type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title / Heading</label>
                        <input type="text" name="title" required value="{{ $template->title }}" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Body Content</label>
                        <textarea name="body" rows="10" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm">{{ $template->body }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Left (Signature)</label>
                            <input type="text" name="footer_left" value="{{ $template->footer_left }}" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Right (Date/Stamp)</label>
                            <input type="text" name="footer_right" value="{{ $template->footer_right }}" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('admin.certificates.templates.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all">Update Template</button>
                    </div>
                </form>
            </div>

            <!-- Help/Placeholders Section -->
            <div class="lg:col-span-1">
                <div class="bg-blue-50 rounded-xl p-6 border border-blue-100 sticky top-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-code text-blue-600"></i> Placeholders
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Copy and paste these variables into your Body Content. They will be replaced dynamically.</p>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-2 bg-white rounded border border-blue-100 text-xs">
                            <code class="text-blue-600 font-bold">@{{student_name}}</code>
                            <span class="text-gray-400">Student Full Name</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-white rounded border border-blue-100 text-xs">
                            <code class="text-blue-600 font-bold">@{{father_name}}</code>
                            <span class="text-gray-400">Father's Name</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-white rounded border border-blue-100 text-xs">
                            <code class="text-blue-600 font-bold">@{{roll_no}}</code>
                            <span class="text-gray-400">Roll/Reg No</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-white rounded border border-blue-100 text-xs">
                            <code class="text-blue-600 font-bold">@{{class}}</code>
                            <span class="text-gray-400">Current Class</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-white rounded border border-blue-100 text-xs">
                            <code class="text-blue-600 font-bold">@{{dob}}</code>
                            <span class="text-gray-400">Date of Birth</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-white rounded border border-blue-100 text-xs">
                            <code class="text-blue-600 font-bold">@{{admission_date}}</code>
                            <span class="text-gray-400">Date of Joining</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-white rounded border border-blue-100 text-xs">
                            <code class="text-blue-600 font-bold">@{{issue_date}}</code>
                            <span class="text-gray-400">Today's Date</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-white rounded border border-blue-100 text-xs">
                            <code class="text-blue-600 font-bold">@{{principal_name}}</code>
                            <span class="text-gray-400">Issuer Name</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection