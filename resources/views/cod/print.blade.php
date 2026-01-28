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

<body onload="window.print()">

@php
    // Extract first 6-digit PIN from address
    preg_match('/\b\d{6}\b/', $slip->address ?? '', $pinMatch);
    $pin = $pinMatch[0] ?? '';

    // Split address into lines
    $addressLines = preg_split('/\r\n|\r|\n/', $slip->address ?? '');
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

        @php $addressPrinted = false; @endphp

        @foreach($addressLines as $line)
            @php $line = trim($line); @endphp
            @if($line !== '')

                {{-- Handle "State & Pincode" or "State" --}}
                @if(stripos($line, 'state') === 0)
                    @php
                        $clean = preg_replace('/^state\s*&?\s*pincode\s*:?/i', '', $line);
                        $clean = preg_replace('/^state\s*:?/i', '', $clean);
                        $parts = preg_split('/[-–]/', trim($clean));
                        $state = trim($parts[0] ?? '');
                        $pin2  = trim($parts[1] ?? '');
                    @endphp

                    @if($pin2)
                        <strong>Pincode :</strong> {{ $pin2 }}<br>
                    @endif
                    <strong>State :</strong> {{ $state }}<br>

                {{-- Normalize "Address Line 1" --}}
                @elseif(stripos($line, 'address line 1') === 0)
                    @php [$k, $v] = explode(':', $line, 2); @endphp
                    <strong>Address:</strong> {{ trim($v) }}<br>
                    @php $addressPrinted = true; @endphp

                {{-- Any other Key: Value --}}
                @elseif(strpos($line, ':') !== false)
                    @php [$k, $v] = explode(':', $line, 2); @endphp
                    <strong>{{ trim($k) }}:</strong> {{ trim($v) }}<br>

                {{-- First plain line becomes Address --}}
                @elseif(!$addressPrinted)
                    <strong>Address:</strong> {{ $line }}<br>
                    @php $addressPrinted = true; @endphp
                @endif

            @endif
        @endforeach

        <strong>Contact Number:</strong> {{ $slip->mobile }}<br>
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
