@php
$routePrefix = ($guard === 'accountant') ? 'accountant.' : 'admin.';
@endphp

@if($guard === 'accountant')
@extends('layouts.accountant')
@else
@extends('layouts.admin')
@endif

@section('title', 'Family: ' . $family->family_code)

@section('content')
<div class="px-6 py-6 max-w-5xl mx-auto">

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route($routePrefix . 'families.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Families
        </a>
    </div>

    {{-- Family Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-extrabold text-xl shadow-lg shadow-indigo-500/30">
                    {{ strtoupper(substr($family->father_name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <h1 class="text-xl font-bold text-gray-900">{{ $family->father_name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold font-mono">
                            {{ $family->family_code }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500">Registered Family Unit</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-3xl font-extrabold text-indigo-600">{{ $family->students->count() }}</p>
                <p class="text-xs text-gray-500 font-medium">Children</p>
            </div>
        </div>

        {{-- Contact Details --}}
        <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-envelope text-blue-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Email</p>
                    <p class="text-sm text-gray-800 font-semibold break-all">{{ $family->email }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-phone text-emerald-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Phone</p>
                    <p class="text-sm text-gray-800 font-semibold">{{ $family->phone ?: 'N/A' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-location-dot text-amber-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Address</p>
                    <p class="text-sm text-gray-800 font-semibold">{{ $family->address ?: 'Not provided' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Children Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <i class="fa-solid fa-children text-indigo-500"></i>
            <h2 class="text-base font-bold text-gray-900">Children ({{ $family->students->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Student Name</th>
                        <th class="px-6 py-3 font-semibold">Class</th>
                        <th class="px-6 py-3 font-semibold">Roll No.</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold text-right">Profile</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($family->students as $i => $student)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-gray-900 text-sm">{{ $student->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ optional($student->schoolClass)->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ $student->roll_number ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $student->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($student->status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($guard === 'admin')
                            <a href="{{ route('admin.students.show', $student->id) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 text-xs font-semibold hover:bg-indigo-600 hover:text-white transition-all">
                                <i class="fa-solid fa-arrow-right"></i>
                                View
                            </a>
                            @else
                            <a href="{{ route('accountant.students.show', $student->id) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 text-xs font-semibold hover:bg-indigo-600 hover:text-white transition-all">
                                <i class="fa-solid fa-arrow-right"></i>
                                View
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">
                            <i class="fa-solid fa-child text-2xl mb-2 block"></i>
                            No students linked to this family yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection