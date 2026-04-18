@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Issued Certificates</h2>
            <p class="text-sm text-gray-500 mt-1">History of all certificates issued to students.</p>
        </div>
        <a href="{{ route('admin.certificates.create') }}" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Issue New
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Certificate No</th>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Issue Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($certificates as $cert)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs font-bold text-gray-800">
                            {{ $cert->certificate_no }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                    {{ substr($cert->student->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $cert->student->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $cert->student->schoolClass->name ?? 'N/A' }} | {{ $cert->student->roll_no }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $cert->template->type->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $cert->issue_date->format('d M, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Issued
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.certificates.show', $cert->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs flex items-center justify-end gap-1">
                                <i class="fa-solid fa-print"></i> Print
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <p class="font-medium">No certificates issued yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $certificates->links() }}
        </div>
    </div>
</div>
@endsection