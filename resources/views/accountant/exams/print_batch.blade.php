<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch Print Admit Cards - {{ $class->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .admit-card-container {
            width: 210mm;
            /* A4 width */
            min-height: 297mm;
            /* A4 height */
            padding: 10mm;
            box-sizing: border-box;
            page-break-after: always;
            position: relative;
        }

        .admit-card-container:last-child {
            page-break-after: auto;
        }

        /* Reusing some styles from admit_card.blade.php but inlined for pure print safety */
        .header-section {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .school-info h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }

        .school-info p {
            margin: 2px 0 0;
            font-size: 12px;
        }

        .exam-title {
            text-align: center;
            background: #000;
            color: #fff;
            padding: 5px;
            margin: 10px 0;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
        }

        .student-details {
            display: flex;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .details-col {
            flex: 1;
        }

        .details-col p {
            margin: 5px 0;
            font-size: 14px;
        }

        .details-col strong {
            display: inline-block;
            width: 100px;
        }

        .photo-box {
            width: 100px;
            height: 120px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #ccc;
            margin-left: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f0f0f0;
        }

        .footer-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            text-align: center;
            padding-top: 40px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 150px;
            padding-top: 5px;
            font-size: 12px;
        }

        .blocked-watermark {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(255, 0, 0, 0.2);
            font-weight: bold;
            border: 5px solid rgba(255, 0, 0, 0.2);
            padding: 20px;
            z-index: 0;
            pointer-events: none;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    @foreach($filteredStudents as $student)
    <div class="admit-card-container">

        <!-- Optional: Watermark for Unpaid if printed accidentally in 'All' mode -->
        @if($student->pending_balance > 0)
        <div class="blocked-watermark">DUES PENDING</div>
        @endif

        <div class="header-section">
            <div class="school-info">
                <h1>Own Education</h1>
                <p>Excellence in Education</p>
                <p>Phone: 123-456-7890 | Email: info@owneducation.com</p>
            </div>
            <!-- Logo placeholder -->
            <div style="font-weight: bold; font-size: 20px;">[LOGO]</div>
        </div>

        <div class="exam-title">
            {{ $activeTerm->name }} - Admit Card
        </div>

        <div class="student-details">
            <div class="details-col">
                <p><strong>Name:</strong> {{ $student->name }}</p>
                <p><strong>Father:</strong> {{ $student->parent ? $student->parent->father_name : 'N/A' }}</p>
                <p><strong>Class:</strong> {{ $class->name }}</p>
                <p><strong>Roll No:</strong> {{ $student->roll_number ?? 'N/A' }}</p>
            </div>
            <div class="photo-box">
                PHOTO
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Subject</th>
                    <th>Room</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $exam)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($exam->exam_date)->format('d M, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($exam->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('h:i A') }}</td>
                    <td>{{ $exam->subject->name ?? 'N/A' }}</td>
                    <td>{{ $exam->room ?? 'TBA' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Instructions -->
        <div style="margin-top: 30px; font-size: 12px; border: 1px dashed #ccc; padding: 10px;">
            <strong> Instructions:</strong>
            <ul style="margin: 5px 0 0 20px; padding: 0;">
                <li>Student must bring this admit card to the examination hall.</li>
                <li>Do not carry mobile phones or any electronic gadgets.</li>
                <li>Reach the exam center 30 minutes before the scheduled time.</li>
            </ul>
        </div>

        <div class="footer-section">
            <div>
                <div class="signature-line">Class Teacher</div>
            </div>
            <div>
                <div class="signature-line">Principal</div>
            </div>
        </div>

        <div style="position: absolute; bottom: 10px; left: 10px; font-size: 10px; color: #999;">
            Generated on: {{ date('d M, Y h:i A') }}
        </div>
    </div>
    @endforeach

</body>

</html>