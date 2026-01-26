<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 12px; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
        }
        .label {
            border: 2px solid #000;
            padding: 8px;
        }
        .row {
            display: table;
            width: 100%;
        }
        .col {
            display: table-cell;
            vertical-align: top;
        }
        .box {
            border: 1px solid #000;
            padding: 6px;
            margin-top: 6px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .big {
            font-size: 16px;
            font-weight: bold;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .muted { font-size: 10px; }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
    </style>
</head>

<body>

@php
    // Extract first 6-digit PIN from address
    preg_match('/\b\d{6}\b/', $slip->address ?? '', $pinMatch);
    $pin = $pinMatch[0] ?? '';
@endphp

<div class="label">

    <!-- Header -->
    <div class="row">
        <div class="col big">
            Pincode: {{ $pin }}
        </div>
        <div class="col right big">
            COD Amount: Rs.{{ number_format($slip->cod_amount) }}
        </div>
    </div>

    <div class="center title" style="margin-top:4px;">
        BUSINESS PARCEL<br>
        CASH ON DELIVERY
    </div>

    <div class="center muted" style="margin-top:4px;">
        Payment Office : {{ $slip->payment_office ?? 'Rajkot H.O.' }} <br>
        Register No / E-Biller ID : {{ $slip->customer_id }}
    </div>

    <div class="divider"></div>

    <!-- To -->
    <div class="box">
        <strong>To,</strong><br>
        <strong>Name:</strong> {{ $slip->customer_name }}<br>
        <strong>Address:</strong><br>
        {!! nl2br(e($slip->address)) !!}<br>
        <strong>Phone 1:</strong> {{ $slip->mobile }}<br>
        @if($slip->alt_mobile)
            <strong>Relative Phone:</strong> {{ $slip->alt_mobile }}<br>
        @endif
    </div>

    <!-- From -->
    <div class="box">
        <strong>From,</strong><br>
        <strong>Krushify Agro Pvt. Ltd.</strong><br>
        Srp Camp, New 150ft Ring Road,<br>
        Ghanteshwar, Bapa Sitaram Chowk,<br>
        Vardhman Sheri Block No: 22<br>
        Gujarat<br>
        Email: info@krushifyagro.com<br>
        Mobile: 9199125925<br>
        GST: 24AAMCK0386L1Z6
    </div>

    <div class="divider"></div>

    <!-- Footer -->
    <div class="muted center">
        If article undelivered, please arrange return to Rajkot H.O.<br>
        “I hereby certify that this article does not contain any dangerous or prohibited goods according to Indian Post rules.”
    </div>

</div>

</body>
</html>
