@php
$layout = request()->routeIs('admin.*') ? 'layouts.admin' : 'layouts.accountant';
$route = request()->routeIs('admin.*') ? route('admin.students.import.process') : route('accountant.students.import.process');
$backRoute = request()->routeIs('admin.*') ? route('admin.students') : route('accountant.students.index');
@endphp

@extends($layout)

@section('title', 'Bulk Student Import')

@section('content')
<div class="sm:p-6 p-4">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Bulk Student Import</h1>
            <p class="text-sm text-gray-500 mt-1">Register multiple students at once using Excel or CSV files.</p>
        </div>
        <a href="{{ $backRoute }}" class="text-sm bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg font-medium shadow-sm transition flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Students
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-xl"></i>
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
        <p class="font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Import Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-4">Choose File (Excel or CSV)</label>
                        <div class="relative group">
                            <input type="file" name="file" id="file-upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                            <div class="border-2 border-dashed border-gray-200 group-hover:border-indigo-400 rounded-2xl p-12 text-center transition-all bg-gray-50 group-hover:bg-indigo-50/30">
                                <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-file-excel text-2xl text-emerald-500"></i>
                                </div>
                                <p id="file-name" class="text-gray-600 font-medium">Drag & drop your file here or click to browse</p>
                                <p class="text-gray-400 text-xs mt-2">Supports .xlsx, .xls, .csv (Max 10MB)</p>
                            </div>
                        </div>
                        @error('file')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4 p-4 bg-amber-50 border border-amber-100 rounded-xl mb-8">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-lg"></i>
                        <p class="text-sm text-amber-800">
                            <strong>Note:</strong> Passwords will be automatically generated using the formula: 
                            <code class="bg-amber-100 px-1.5 py-0.5 rounded font-bold text-amber-900">ClassID + RollNumber</code>.
                        </p>
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-upload"></i>
                        Start Import Process
                    </button>
                </form>
            </div>
        </div>

        <!-- Instructions -->
        <div class="lg:col-span-1">
            <div class="bg-gray-900 rounded-2xl p-6 text-white shadow-xl h-full">
                <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-blue-400"></i>
                    Import Instructions
                </h3>
                
                <ul class="space-y-6">
                    <li class="flex gap-4">
                        <div class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs font-bold flex-shrink-0">1</div>
                        <div>
                            <p class="font-semibold text-sm">Download Template</p>
                            <p class="text-gray-400 text-xs mt-1">Ensure your file has the correct headers: <span class="text-blue-300">name, email, class, gender, dob, parent_phone, parent_name</span>.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs font-bold flex-shrink-0">2</div>
                        <div>
                            <p class="font-semibold text-sm">Class Names</p>
                            <p class="text-gray-400 text-xs mt-1">The "class" column must match an existing class name exactly (e.g., "Class 1"). If it doesn't exist, it will be created.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs font-bold flex-shrink-0">3</div>
                        <div>
                            <p class="font-semibold text-sm">Parent Linking</p>
                            <p class="text-gray-400 text-xs mt-1">Providing a <span class="text-blue-300">parent_phone</span> will automatically link the student to an existing parent or create a new one.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs font-bold flex-shrink-0">4</div>
                        <div>
                            <p class="font-semibold text-sm">Auto-Passwords</p>
                            <p class="text-gray-400 text-xs mt-1">No need to provide passwords. They are generated automatically so admins can easily communicate them.</p>
                        </div>
                    </li>
                </ul>

                <div class="mt-10 p-4 bg-white/5 border border-white/10 rounded-xl">
                    <p class="text-xs text-gray-400 leading-relaxed italic">
                        "Bulk import is the fastest way to set up your entire school roster in minutes."
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('file-upload').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Drag & drop your file here or click to browse';
        document.getElementById('file-name').innerText = fileName;
        document.getElementById('file-name').classList.add('text-indigo-600', 'font-bold');
    });
</script>
@endsection
