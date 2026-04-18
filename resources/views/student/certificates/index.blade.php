@extends('layouts.student')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">My Certificates</h2>
        <p class="text-sm text-gray-500 mt-1">View and download your official school certificates.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Certificate Type</th>
                        <th class="px-6 py-4">Certificate No</th>
                        <th class="px-6 py-4">Issue Date</th>
                        <th class="px-6 py-4 text-right">Download</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($certificates as $cert)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <i class="fa-solid fa-certificate"></i>
                                </div>
                                <span class="font-bold text-gray-900">{{ $cert->template->title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">
                            {{ $cert->certificate_no }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $cert->issue_date->format('d M, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('student.certificates.show', $cert->id) }}" target="_blank" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-sm transition-all inline-flex items-center gap-2">
                                <i class="fa-solid fa-download"></i> Download PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-regular fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                <p class="font-medium">No certificates found</p>
                                <p class="text-xs text-gray-400 mt-1">Certificates issued by the school will appear here.</p>
                            </div>
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