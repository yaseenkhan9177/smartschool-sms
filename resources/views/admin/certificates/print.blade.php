@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center print:hidden">
        <a href="{{ route('admin.certificates.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 shadow-lg flex items-center gap-2">
            <i class="fa-solid fa-print"></i> Print / Download PDF
        </button>
    </div>

    <!-- Certificate Container (Reused Styles from Preview) -->
    <div class="certificate-container bg-white shadow-2xl mx-auto relative overflow-hidden print:shadow-none print:w-full print:border-none">

        <!-- Border/Frame -->
        <div class="absolute inset-4 border-4 border-double border-gray-800 pointer-events-none"></div>

        <!-- Serial No -->
        <div class="absolute top-8 right-8 text-xs font-mono text-gray-400">
            No: {{ $certificate->certificate_no }}
        </div>

        <!-- Header -->
        <div class="text-center pt-16 pb-8">
            <div class="w-24 h-24 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                <i class="fa-solid fa-school text-4xl text-gray-400"></i>
            </div>

            <h1 class="text-4xl font-serif font-bold text-gray-900 tracking-wide uppercase mb-2">{{ $certificate->template->title }}</h1>
            <p class="text-gray-500 font-serif italic">This certifies that</p>
        </div>

        <!-- Body -->
        <div class="px-20 py-8 text-center">
            <div class="text-xl font-serif leading-loose text-gray-800">
                {!! nl2br(e($content)) !!}
            </div>
        </div>

        <!-- Footer -->
        <div class="px-20 pt-16 pb-20 flex justify-between items-end mt-auto">
            <div class="text-center">
                <div class="w-48 border-b-2 border-gray-800 mb-2"></div>
                <p class="font-bold text-gray-900 uppercase text-sm">{{ $certificate->template->footer_left }}</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 border-2 border-gray-300 rounded-full mx-auto mb-2 flex items-center justify-center text-gray-300 text-xs">
                    STAMP
                </div>
            </div>

            <div class="text-center">
                <div class="w-48 border-b-2 border-gray-800 mb-2"></div>
                <p class="font-bold text-gray-900 uppercase text-sm">{{ $certificate->template->footer_right }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $certificate->issue_date->format('d M, Y') }}</p>
            </div>
        </div>

        <!-- Watermark -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-5 pointer-events-none print:opacity-[0.03]">
            <i class="fa-solid fa-certificate text-[400px]"></i>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap');

    .certificate-container {
        width: 210mm;
        min-height: 297mm;
        /* A4 */
        font-family: 'Playfair Display', serif;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 0;
        }

        body {
            background: white;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        .print\:hidden {
            display: none !important;
        }

        .print\:shadow-none {
            box-shadow: none !important;
        }

        .container {
            width: 100% !important;
            max-width: none !important;
            padding: 0 !important;
        }

        .certificate-container {
            margin: 0 auto;
            width: 100%;
            height: 100vh;
        }
    }
</style>
@endsection