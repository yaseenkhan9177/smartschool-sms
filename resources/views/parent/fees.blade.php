<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Portal | Fee History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 font-[Inter] antialiased min-h-screen flex flex-col">

    <!-- Header -->
    <header class="hero-gradient text-white pt-8 pb-16 px-4 sm:px-6 lg:px-8 shadow-xl relative z-10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">

            @if($currentStudent)
            <div class="flex items-center gap-6 w-full md:w-auto">
                <a href="{{ route('parent.dashboard', ['student_id' => $currentStudent->id]) }}" class="text-white/80 hover:text-white transition">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <div class="text-center md:text-left">
                    <h1 class="text-3xl font-bold tracking-tight">Fee History</h1>
                    <p class="text-indigo-100 text-lg mt-1 font-medium">{{ $currentStudent->name }}</p>
                </div>
            </div>

            <!-- Right Side: Switch Child & Log Out -->
            <div class="flex items-center gap-3">
                @if($students->count() > 1)
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 px-4 py-2.5 rounded-xl transition backdrop-blur-sm border border-white/10">
                        <span class="text-sm font-medium">Switch Child</span>
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>
                    <!-- Dropdown -->
                    <div x-show="open" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl overflow-hidden py-1 z-50 text-gray-800" x-cloak>
                        @foreach($students as $s)
                        <a href="{{ route('parent.fees', ['student_id' => $s->id]) }}" class="block px-4 py-3 hover:bg-gray-50 {{ $s->id === $currentStudent->id ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                            {{ $s->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <a href="{{ route('logout') }}" class="bg-white/10 hover:bg-red-500/80 px-4 py-2.5 rounded-xl transition backdrop-blur-sm border border-white/10" title="Logout">
                    <i class="fa-solid fa-power-off"></i>
                </a>
            </div>
            @else
            <div class="text-center w-full">
                <h1 class="text-2xl font-bold">No Students Linked</h1>
            </div>
            @endif

        </div>
    </header>

    @if($currentStudent)
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 flex-1 w-full pb-12">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-200">
                        <tr>
                            <th class="py-4 px-6">Invoice No</th>
                            <th class="py-4 px-6">Month</th>
                            <th class="py-4 px-6">Due Date</th>
                            <th class="py-4 px-6">Amount</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($currentStudent->studentFees as $fee)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-6 font-medium text-gray-900">#INV-{{ $fee->id }}</td>
                            <td class="py-4 px-6">{{ $fee->month_year }}</td>
                            <td class="py-4 px-6">{{ \Carbon\Carbon::parse($fee->due_date)->format('M d, Y') }}</td>
                            <td class="py-4 px-6 font-bold">Rs {{ number_format($fee->amount) }}</td>
                            <td class="py-4 px-6">
                                @if($fee->status == 'paid')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Paid
                                </span>
                                @elseif($fee->status == 'partial')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    Partial
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Unpaid
                                </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($fee->status != 'paid')
                                    <button class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-indigo-700 transition">
                                        Pay Now
                                    </button>
                                    @endif
                                    <!-- Using # for now as we don't have direct download route, or we can assume one exists for student -->
                                    <button class="text-gray-400 hover:text-gray-600 transition" title="Download Invoice">
                                        <i class="fa-solid fa-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                No fee history found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    @endif

    <footer class="mt-auto py-6 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} Own Education Systems. All rights reserved.
    </footer>

</body>

</html>