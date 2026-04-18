@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ reportModal: false, selectedReport: null, actionType: '' }">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Student Reports</h2>
            <p class="text-gray-500 text-sm mt-1">Manage and review disciplinary reports</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Reported By</th>
                        <th class="px-6 py-4">Severity</th>
                        <th class="px-6 py-4">Reason</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $report->student->name ?? 'Unknown' }}
                            <div class="text-xs text-gray-400 font-normal">{{ $report->student->schoolClass->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $report->reporter_name }}</div>
                            <div class="text-xs text-gray-400 capitalize">{{ $report->reporter_role }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($report->severity == 'high')
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full uppercase">High</span>
                            @elseif($report->severity == 'medium')
                            <span class="bg-orange-100 text-orange-600 text-xs font-bold px-2 py-1 rounded-full uppercase">Medium</span>
                            @else
                            <span class="bg-yellow-100 text-yellow-600 text-xs font-bold px-2 py-1 rounded-full uppercase">Low</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="line-clamp-2" title="{{ $report->reason }}">{{ $report->reason }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($report->status == 'pending')
                            <span class="bg-blue-100 text-blue-600 text-xs font-bold px-2 py-1 rounded-full uppercase">Pending</span>
                            @elseif($report->status == 'resolved')
                            <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded-full uppercase">Resolved</span>
                            @elseif($report->status == 'escalated')
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full uppercase">Escalated</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs">
                            {{ $report->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($report->status == 'pending')
                            <div class="flex items-center justify-end gap-2">
                                <button @click="reportModal = true; selectedReport = {{ $report->id }}; actionType = 'resolve'" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors text-xs font-bold flex items-center gap-1 border border-gray-200" title="Resolve Internally">
                                    <i class="fa-solid fa-check"></i> Resolve
                                </button>
                                <button @click="reportModal = true; selectedReport = {{ $report->id }}; actionType = 'escalate'" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors text-xs font-bold flex items-center gap-1 border border-red-100" title="Escalate to Parent">
                                    <i class="fa-solid fa-bullhorn"></i> Escalate
                                </button>
                            </div>
                            @elseif($report->status == 'resolved')
                            <span class="text-xs text-gray-400 italic">Resolved internally</span>
                            @elseif($report->status == 'escalated')
                            <span class="text-xs text-red-500 font-bold"><i class="fa-solid fa-share-from-square"></i> Sent to Parent</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-check-circle text-4xl mb-3 text-green-100"></i>
                                <p class="font-medium text-gray-900">No reports found.</p>
                                <p class="text-xs text-gray-400 mt-1">Good discipline record!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Modal -->
    <div x-show="reportModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6" x-cloak>
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="reportModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up transition-all transform border border-gray-100">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid" :class="actionType == 'escalate' ? 'fa-bullhorn text-red-500' : 'fa-check-circle text-green-500'"></i>
                        <span x-text="actionType == 'escalate' ? 'Escalate Report' : 'Resolve Report'"></span>
                    </h3>
                    <button @click="reportModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>

                <p class="text-sm text-gray-500 mb-4" x-show="actionType == 'escalate'">
                    Warning: This action will <strong>notify the parent</strong> immediately via Email and SMS/WhatsApp.
                </p>
                <p class="text-sm text-gray-500 mb-4" x-show="actionType == 'resolve'">
                    This will resolve the report internally without notifying privacy.
                </p>

                <!-- Dynamic Form -->
                <form x-bind:action="'/admin/reports/' + selectedReport + '/update'" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="action" :value="actionType">

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Resolution / Note</label>
                        <textarea name="note" rows="3" placeholder="Add a note..." class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="button" @click="reportModal = false" class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-bold text-sm transition-colors">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2 text-white rounded-xl font-bold text-sm shadow-lg transition-colors"
                            :class="actionType == 'escalate' ? 'bg-red-600 hover:bg-red-700 shadow-red-200' : 'bg-green-600 hover:bg-green-700 shadow-green-200'">
                            Confirm Action
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection