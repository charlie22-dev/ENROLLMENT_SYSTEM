<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Certificate of Registration — {{ $pef->student->student_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 3px 0;
            font-size: 14px;
            color: #555;
        }
        .header p {
            margin: 2px 0;
            font-size: 11px;
            font-style: italic;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #555;
            width: 15%;
        }
        .val {
            width: 35%;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table-data th, .table-data td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        .table-data th {
            background-color: #f2f2f2;
            font-size: 11px;
            text-transform: uppercase;
        }
        .fee-table {
            width: 50%;
            float: right;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .fee-table td {
            padding: 3px 6px;
            text-align: right;
        }
        .fee-table .label-cell {
            text-align: left;
        }
        .total-row {
            font-weight: bold;
            border-top: 1px solid #333;
        }
        .clear {
            clear: both;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px dashed #aaa;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .badge {
            display: inline-block;
            background: #22c55e;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
        }
        .no-print {
            margin-bottom: 20px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #4f46e5; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Print COR</button>
        <a href="{{ route('student.pef.cor.pdf', $pef) }}" style="display: inline-block; padding: 8px 16px; background: #059669; color: white; border-radius: 4px; text-decoration: none; font-weight: bold; margin-left: 8px;">Download PDF</a>
        <a href="{{ route('student.dashboard') }}" style="display: inline-block; padding: 8px 16px; background: #6b7280; color: white; border-radius: 4px; text-decoration: none; font-weight: bold; margin-left: 8px;">Dashboard</a>
    </div>

    <div class="header">
        <h1>{{ config('app.name', 'Enrollment System') }}</h1>
        <h2>OFFICIAL CERTIFICATE OF REGISTRATION (COR)</h2>
        <p>Office of the University Registrar</p>
        <p><strong>{{ $pef->term->label() }}</strong></p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Student Name:</td>
            <td class="val"><strong>{{ $pef->student->user->name }}</strong></td>
            <td class="label">Student No:</td>
            <td class="val"><strong>{{ $pef->student->student_no }}</strong></td>
        </tr>
        <tr>
            <td class="label">Program:</td>
            <td class="val">{{ $pef->student->program->name }} ({{ $pef->student->program->code }})</td>
            <td class="label">Year Level:</td>
            <td class="val">Year {{ $pef->student->year_level }}</td>
        </tr>
        <tr>
            <td class="label">Status:</td>
            <td class="val"><span class="badge">OFFICIALLY ENROLLED</span></td>
            <td class="label">Total Units:</td>
            <td class="val"><strong>{{ $pef->total_units }} units</strong></td>
        </tr>
    </table>

    <table class="table-data">
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject Description</th>
                <th>Sec</th>
                <th>Units</th>
                <th>Schedule</th>
                <th>Room</th>
                <th>Instructor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pef->items as $item)
            <tr>
                <td><strong>{{ $item->section->subject->code }}</strong></td>
                <td>{{ $item->section->subject->title }}</td>
                <td style="text-align: center;">{{ $item->section->section_code }}</td>
                <td style="text-align: center;">{{ $item->section->subject->units }}</td>
                <td>
                    @foreach($item->section->schedules as $s)
                        <div>{{ $s->day }} {{ substr($s->start_time,0,5) }}–{{ substr($s->end_time,0,5) }}</div>
                    @endforeach
                </td>
                <td>{{ $item->section->room?->name ?? 'TBA' }}</td>
                <td>{{ $item->section->instructor?->name ?? 'TBA' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($pef->assessment)
    <table class="fee-table">
        <tr>
            <td class="label-cell">Tuition ({{ $pef->total_units }} units):</td>
            <td>₱{{ number_format($pef->assessment->tuition, 2) }}</td>
        </tr>
        @foreach($pef->assessment->misc_fees as $fee)
        <tr>
            <td class="label-cell">{{ $fee['name'] }}:</td>
            <td>₱{{ number_format($fee['amount'], 2) }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td class="label-cell">Total Assessment:</td>
            <td>₱{{ number_format($pef->assessment->total, 2) }}</td>
        </tr>
        @foreach($pef->payments as $payment)
        <tr style="color: green; font-weight: bold;">
            <td class="label-cell">Paid (OR #{{ $payment->or_number }}):</td>
            <td>₱{{ number_format($payment->amount, 2) }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <div class="clear"></div>

    <div class="footer">
        <p>This is an officially generated Certificate of Registration from the {{ config('app.name') }}.</p>
        <p>Date Generated: {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>
