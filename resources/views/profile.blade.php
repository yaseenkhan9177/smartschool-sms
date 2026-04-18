@extends('layouts.student')

@section('header', 'My Profile')

@section('content')
<!-- Top Stats Bar -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Attendance -->
    <div class="glass-card p-5 rounded-2xl flex items-center justify-between group hover:border-emerald-500/30 transition-all">
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider mb-1">Attendance</p>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $attendanceRate }}<span class="text-lg text-emerald-500 dark:text-emerald-400">%</span></h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-xl border border-emerald-200 dark:border-emerald-500/20 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
    </div>

    <!-- Grade Average -->
    <div class="glass-card p-5 rounded-2xl flex items-center justify-between group hover:border-purple-500/30 transition-all">
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider mb-1">Grade Average</p>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $gradeLetter }} <span class="text-sm font-normal text-gray-400">({{ $averagePercentage }}%)</span></h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center text-purple-600 dark:text-purple-400 text-xl border border-purple-200 dark:border-purple-500/20 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
    </div>

    <!-- Fees Summary -->
    <div class="glass-card p-5 rounded-2xl flex items-center justify-between group hover:border-red-500/30 transition-all">
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider mb-1">Total Fees ({{ now()->year }})</p>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-white">PKR {{ number_format($totalAnnualFees) }}</h3>
            <div class="flex gap-3 mt-1 text-xs font-medium">
                <span class="text-green-600 bg-green-100 px-1.5 py-0.5 rounded">Paid: {{ number_format($totalPaidYearly) }}</span>
                <span class="text-red-600 bg-red-100 px-1.5 py-0.5 rounded">Due: {{ number_format($feesDue) }}</span>
            </div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center text-red-600 dark:text-red-400 text-xl border border-red-200 dark:border-red-500/20 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: ID Card Style Profile (30%) -->
    <div class="space-y-6">
        <!-- ID Card -->
        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-3xl p-0 overflow-hidden relative shadow-2xl">
            <!-- ID Card Hanger Hole (Visual) -->
            <div class="absolute top-4 left-1/2 -translate-x-1/2 w-16 h-2 bg-gray-200 dark:bg-slate-900 rounded-full z-10"></div>

            <!-- Header Pattern -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 dark:from-blue-900 dark:to-slate-900 p-6 pt-10 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-xl"></div>
                <div class="relative w-32 h-32 mx-auto mb-4">
                    <img src="{{ asset($student->profile_image ?? 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=random') }}"
                        class="w-full h-full rounded-2xl object-cover border-4 border-white dark:border-slate-700 shadow-xl">
                    <div class="absolute -bottom-2 -right-2 bg-green-500 text-white dark:text-slate-900 text-xs font-bold px-2 py-0.5 rounded shadow-lg border-2 border-white dark:border-slate-800">ACTIVE</div>
                </div>
                <h2 class="text-xl font-bold text-white mb-0.5">{{ $student->name }}</h2>
                <p class="text-blue-100 dark:text-blue-400 text-xs font-bold uppercase tracking-widest mb-4">Student</p>
            </div>

            <!-- Details Details -->
            <div class="p-6 bg-white dark:bg-slate-800/50 backdrop-blur-sm">
                <div class="space-y-3">
                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-slate-700 pb-2">
                        <span class="text-gray-500 dark:text-slate-500 text-xs font-bold uppercase">ID Number</span>
                        <span class="text-gray-700 dark:text-slate-200 font-mono">{{ $student->student_id ?? 'STD-8842' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-slate-700 pb-2">
                        <span class="text-gray-500 dark:text-slate-500 text-xs font-bold uppercase">Class</span>
                        <span class="text-gray-700 dark:text-slate-200">{{ $student->department }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-slate-700 pb-2">
                        <span class="text-gray-500 dark:text-slate-500 text-xs font-bold uppercase">Roll No</span>
                        <span class="text-gray-700 dark:text-slate-200">#24</span>
                    </div>
                </div>

                <div class="mt-6 flex gap-2">
                    <button class="flex-1 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold uppercase rounded-lg transition-colors">
                        Download ID
                    </button>
                    <button class="px-3 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-500 dark:text-slate-300 rounded-lg transition-colors">
                        <i class="fa-solid fa-qrcode"></i>
                    </button>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="h-2 bg-gradient-to-r from-red-500 via-yellow-500 to-blue-500"></div>
        </div>
    </div>

    <!-- Right Column: Tabs (70%) -->
    <div class="lg:col-span-2" x-data="{ tab: 'personal' }">
        <!-- Tab Navigation -->
        <div class="glass-card p-1 flex mb-6 rounded-2xl">
            <button @click="tab = 'personal'" :class="tab === 'personal' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white'" class="flex-1 py-2.5 rounded-xl text-sm font-medium transition-all duration-300">
                <i class="fa-regular fa-user mr-2"></i> Personal Info
            </button>
            <button @click="tab = 'parent'" :class="tab === 'parent' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white'" class="flex-1 py-2.5 rounded-xl text-sm font-medium transition-all duration-300">
                <i class="fa-solid fa-users mr-2"></i> Parent Details
            </button>
            <button @click="tab = 'academic'" :class="tab === 'academic' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white'" class="flex-1 py-2.5 rounded-xl text-sm font-medium transition-all duration-300">
                <i class="fa-solid fa-book-open mr-2"></i> Academic History
            </button>
            <button @click="tab = 'fees'" :class="tab === 'fees' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white'" class="flex-1 py-2.5 rounded-xl text-sm font-medium transition-all duration-300">
                <i class="fa-solid fa-file-invoice-dollar mr-2"></i> Fee Status
            </button>
        </div>

        <!-- Tab Content -->
        <div class="glass-card p-8 min-h-[400px] rounded-3xl">

            <!-- Personal Info Tab -->
            <div x-show="tab === 'personal'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-blue-500 rounded-full"></span> Basic Information
                </h3>
                <div class="grid md:grid-cols-2 gap-y-6 gap-x-12">
                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-gray-500 dark:text-slate-500 font-bold mb-1 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">Full Name</label>
                        <p class="text-lg text-gray-800 dark:text-slate-200 font-medium border-b border-gray-200 dark:border-slate-700 pb-2">{{ $student->name }}</p>
                    </div>
                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-gray-500 dark:text-slate-500 font-bold mb-1 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">Email Address</label>
                        <p class="text-lg text-gray-800 dark:text-slate-200 font-medium border-b border-gray-200 dark:border-slate-700 pb-2">{{ $student->email }}</p>
                    </div>
                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-gray-500 dark:text-slate-500 font-bold mb-1 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">Phone Number</label>
                        <p class="text-lg text-gray-800 dark:text-slate-200 font-medium border-b border-gray-200 dark:border-slate-700 pb-2">{{ $student->phone }}</p>
                    </div>
                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-gray-500 dark:text-slate-500 font-bold mb-1 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">Date of Birth</label>
                        <p class="text-lg text-gray-800 dark:text-slate-200 font-medium border-b border-gray-200 dark:border-slate-700 pb-2">Jan 15, 2004</p> <!-- Mock Data -->
                    </div>
                    <div class="group md:col-span-2">
                        <label class="block text-xs uppercase tracking-wider text-gray-500 dark:text-slate-500 font-bold mb-1 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">Address</label>
                        <p class="text-lg text-gray-800 dark:text-slate-200 font-medium border-b border-gray-200 dark:border-slate-700 pb-2">123 Learning Ave, Academic City, AC 56789</p> <!-- Mock Data -->
                    </div>
                </div>
            </div>

            <!-- Parent Details Tab -->
            <div x-show="tab === 'parent'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-green-500 rounded-full"></span> Guardian Information
                </h3>
                <div class="grid md:grid-cols-2 gap-y-6 gap-x-12">
                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-gray-500 dark:text-slate-500 font-bold mb-1 group-hover:text-green-500 dark:group-hover:text-green-400 transition-colors">Father's Name</label>
                        <p class="text-lg text-gray-800 dark:text-slate-200 font-medium border-b border-gray-200 dark:border-slate-700 pb-2">John Doe Sr.</p>
                    </div>
                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-gray-500 dark:text-slate-500 font-bold mb-1 group-hover:text-green-500 dark:group-hover:text-green-400 transition-colors">Contact Number</label>
                        <p class="text-lg text-gray-800 dark:text-slate-200 font-medium border-b border-gray-200 dark:border-slate-700 pb-2">+1 (555) 987-6543</p>
                    </div>
                    <!-- More mock fields if needed -->
                </div>
            </div>

            <!-- Academic History Tab -->
            <div x-show="tab === 'academic'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-purple-500 rounded-full"></span> Enrollments
                </h3>
                <div class="space-y-4">
                    <!-- Current Enrollment -->
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-slate-700/30 border border-gray-200 dark:border-slate-600 flex justify-between items-center">
                        <div>
                            <h4 class="font-bold text-gray-800 dark:text-white">{{ $student->department }}</h4>
                            <p class="text-gray-500 dark:text-slate-400 text-sm">Current, Semester 4</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400 text-xs font-bold border border-green-200 dark:border-green-500/30">Active</span>
                    </div>
                    <!-- Past Enrollment (Mock) -->
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-slate-700/30 border border-gray-200 dark:border-slate-600 flex justify-between items-center opacity-60 hover:opacity-100 transition-opacity">
                        <div>
                            <h4 class="font-bold text-gray-800 dark:text-white">Secondary School</h4>
                            <p class="text-gray-500 dark:text-slate-400 text-sm">Completed, 2022</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-gray-200 dark:bg-slate-600/20 text-gray-600 dark:text-slate-400 text-xs font-bold border border-gray-300 dark:border-slate-600/30">Graduated</span>
                    </div>
                </div>
            </div>

            <!-- Fee Status Tab -->
            <div x-show="tab === 'fees'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-red-500 rounded-full"></span> Outstanding Fees
                </h3>

                @if($unpaidFees->count() > 0)
                <div class="space-y-4">
                    @foreach($unpaidFees as $fee)
                    @php
                    $invoiceTotal = $fee->amount + $fee->late_fee - $fee->discount;
                    $paid = $fee->payments->sum('amount_paid');
                    $balance = max(0, $invoiceTotal - $paid);
                    @endphp
                    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-bold text-gray-800 dark:text-white">
                                    {{ $fee->month ? \Carbon\Carbon::parse($fee->month)->format('F Y') : 'Fee' }}
                                </h4>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $fee->status == 'partial' ? 'bg-orange-100 text-orange-600' : 'bg-red-100 text-red-600' }}">
                                    {{ ucfirst($fee->status) }}
                                </span>
                            </div>
                            <p class="text-gray-500 dark:text-slate-400 text-sm">
                                Due: {{ \Carbon\Carbon::parse($fee->due_date)->format('M d, Y') }} • Total: {{ number_format($invoiceTotal) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Balance Due</p>
                            <span class="text-xl font-bold text-red-600 dark:text-red-400">PKR {{ number_format($balance) }}</span>
                        </div>
                    </div>
                    @endforeach

                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-700 flex justify-between items-center">
                        <span class="font-bold text-gray-800 dark:text-white">Total Outstanding</span>
                        <span class="text-2xl font-bold text-red-600 dark:text-red-400">PKR {{ number_format($feesDue) }}</span>
                    </div>
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">All Clear!</h3>
                    <p class="text-gray-500">You have no outstanding fees.</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection