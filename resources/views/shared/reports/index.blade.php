@extends($layout)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Comprehensive Reports</h2>
            <p class="text-sm text-slate-500 mt-1">A high-level overview of school operations and performance.</p>
        </div>
        <button onclick="window.print()" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all flex items-center gap-2 print:hidden group">
            <i class="fa-solid fa-print group-hover:scale-110 transition-transform"></i> Print Report
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        <!-- Student Statistics Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden group hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-slate-50 bg-gradient-to-r from-blue-50 to-transparent">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-1">Students</p>
                        <h3 class="text-2xl font-black text-slate-800">{{ number_format($totalStudents) }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-slate-500 text-sm font-medium">Active Status</span>
                    <span class="text-emerald-600 text-sm font-bold bg-emerald-50 px-2 py-1 rounded-lg">{{ number_format($activeStudents) }} Active</span>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600 flex items-center gap-2"><i class="fa-solid fa-mars text-blue-400"></i> Boys</span>
                        <span class="font-bold text-slate-800">{{ $genderWise->get('male', 0) }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-blue-400 h-1.5 rounded-full" style="width: {{ $totalStudents > 0 ? round(($genderWise->get('male', 0) / $totalStudents) * 100, 2) : 0 }}%"></div>
                    </div>
                    <div class="flex justify-between text-sm mt-3">
                        <span class="text-slate-600 flex items-center gap-2"><i class="fa-solid fa-venus text-pink-400"></i> Girls</span>
                        <span class="font-bold text-slate-800">{{ $genderWise->get('female', 0) }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-pink-400 h-1.5 rounded-full" style="width: {{ $totalStudents > 0 ? round(($genderWise->get('female', 0) / $totalStudents) * 100, 2) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fee & Finance Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden group hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-slate-50 bg-gradient-to-r from-emerald-50 to-transparent">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-emerald-600 uppercase tracking-widest mb-1">Fee Collection</p>
                        <h3 class="text-2xl font-black text-slate-800"><span class="text-lg text-slate-400 mr-1">Rs.</span>{{ number_format($totalCollected) }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 font-semibold mb-1 uppercase">Late Fine Collected</p>
                        <p class="text-lg font-bold text-amber-600">Rs. {{ number_format($fineCollected) }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 font-semibold mb-1 uppercase">Defaulters</p>
                        <p class="text-lg font-bold text-red-600">{{ number_format($defaultersCount) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff & Operations Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden group hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-slate-50 bg-gradient-to-r from-purple-50 to-transparent">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-purple-600 uppercase tracking-widest mb-1">Teaching Staff</p>
                        <h3 class="text-2xl font-black text-slate-800">{{ number_format($totalTeachers) }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-person-chalkboard"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-5">
                    <div class="flex-1">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-600 font-medium">Today's Attendance</span>
                            <span class="font-bold text-slate-800">{{ $todayAttendance }} / {{ $totalTeachers }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $totalTeachers > 0 ? round(($todayAttendance / $totalTeachers) * 100, 2) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 flex justify-between items-center">
                    <span class="text-xs font-semibold text-slate-500 uppercase">Estimated Salary Scope</span>
                    <span class="text-sm font-bold text-slate-700">Rs. {{ number_format($totalSalaryPaid) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Grids -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Class-wise Distribution -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2"><i class="fa-solid fa-layer-group text-blue-500"></i> Class-wise Strength</h3>
            </div>
            <div class="p-5 max-h-80 overflow-y-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs uppercase bg-slate-50 text-slate-500 font-bold sticky top-0">
                        <tr>
                            <th class="px-4 py-3 rounded-l-lg">Class Name</th>
                            <th class="px-4 py-3 text-right">Students</th>
                            <th class="px-4 py-3 rounded-r-lg text-right">% of Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classWise as $className => $count)
                        <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $className }}</td>
                            <td class="px-4 py-3 text-right font-bold">{{ $count }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-bold">{{ $totalStudents > 0 ? number_format(($count / $totalStudents) * 100, 1) : 0 }}%</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-400">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Block For Exams & System -->
        <div class="space-y-6">
            <!-- Top Students -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-slate-50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2"><i class="fa-solid fa-trophy text-amber-500"></i> Top Performers (Exams)</h3>
                </div>
                <div class="p-5">
                    @forelse($topStudents as $index => $result)
                    <div class="flex items-center justify-between mb-3 last:mb-0 bg-slate-50 p-3 rounded-xl border border-slate-100 hover:border-amber-200 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full {{ $index == 0 ? 'bg-amber-100 text-amber-600 shadow-sm' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-black text-sm">
                                #{{ $index + 1 }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $result->student->name ?? 'Unknown Student' }}</p>
                                <p class="text-xs text-slate-500">{{ $result->student->schoolClass->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-amber-600">{{ $result->total_marks }}</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider relative -top-1">Marks</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-slate-400">
                        <i class="fa-solid fa-medal text-3xl mb-2 opacity-50"></i>
                        <p class="text-sm">No exam data available yet</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- System Activity -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2"><i class="fa-solid fa-server text-slate-500"></i> System Metrics</h3>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-center p-3 bg-indigo-50 rounded-xl border border-indigo-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white text-indigo-600 flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-comment-sms"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-indigo-900">Total SMS Sent</p>
                                <p class="text-xs text-indigo-600/80">Notification system history</p>
                            </div>
                        </div>
                        <span class="text-xl font-black text-indigo-700">{{ number_format($smsSent) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .container,
        .container * {
            visibility: visible;
        }

        .container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endsection