<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - Fee Clearance Required</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 h-screen flex flex-col items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-8 text-center border-t-8 border-red-500">

        <div class="w-20 h-20 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-hand-paper text-4xl"></i>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Admit Card Blocked</h1>
        <p class="text-gray-500 mb-6">
            Your Admit Card cannot be generated due to pending fee dues.
        </p>

        <div class="bg-red-50 border border-red-100 rounded-lg p-4 mb-8">
            <p class="text-sm text-red-600 font-medium uppercase tracking-wider mb-1">Total Outstanding Amount</p>
            <p class="text-3xl font-bold text-red-700">PKR {{ number_format($pendingBalance, 2) }}</p>
        </div>

        <div class="space-y-3">
            <a href="{{ route('student.fees.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors">
                <i class="fas fa-money-bill-wave mr-2"></i> Pay Fees Online
            </a>

            <a href="{{ url()->previous() }}" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl transition-colors">
                Go Back
            </a>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100">
            <p class="text-xs text-gray-400">
                If you believe this is an error, please contact the accounts office immediately.
            </p>
            <div class="flex justify-center gap-4 mt-3 text-sm text-gray-600">
                <span><i class="fas fa-phone mr-1"></i> +123 456 7890</span>
                <span><i class="fas fa-envelope mr-1"></i> accounts@school.edu</span>
            </div>
        </div>

    </div>

</body>

</html>