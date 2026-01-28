<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page { margin: 20px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color:#000; }

        .title {
            text-align:center;
            font-weight:bold;
            font-size:16px;
            border:2px solid #3b82f6;
            padding:6px 0;
            margin-bottom:8px;
        }

        .top {
            width:100%;
            border:1px solid #3b82f6;
            border-collapse:collapse;
        }
        .top td {
            border:1px solid #3b82f6;
            padding:6px;
            vertical-align:top;
        }

        .logo { font-size:18px; font-weight:bold; color:#16a34a; }

        .section {
            width:100%;
            border:1px solid #3b82f6;
            border-top:none;
            border-collapse:collapse;
        }
        .section th, .section td {
            border:1px solid #3b82f6;
            padding:6px;
            vertical-align:top;
        }
        .section th {
            background:#eef6ff;
            text-align:left;
            font-weight:bold;
        }

        table.items {
            width:100%;
            border-collapse:collapse;
            margin-top:6px;
        }
        .items th, .items td {
            border:1px solid #3b82f6;
            padding:5px;
        }
        .items th {
            background:#eef6ff;
            font-weight:bold;
        }

        .right { text-align:right; }
        .center { text-align:center; }

        .totals {
            width:100%;
            border-collapse:collapse;
            margin-top:6px;
        }
        .totals td {
            border:1px solid #3b82f6;
            padding:5px;
        }

        .terms {
            border:1px solid #3b82f6;
            margin-top:8px;
            padding:6px;
            font-size:10px;
        }
    </style>
</head>
<body onload="window.print()">

<div class="title">INVOICE</div>

<table class="top">
    <tr>
        <td width="60%">
            <div class="logo">Krushify Agro Pvt Ltd.</div>
            <strong>Mobile:</strong> 9199125925<br>
            <strong>Address:</strong> The One World (B), 1005, Ayodhya Circle<br>
            <strong>Email:</strong> info@krushifyagro.com<br>
            <strong>GST Number:</strong> 24AAMCK0386L1Z6
        </td>
        <td width="40%">
            <strong>Invoice No:</strong> {{ $invoice->invoice_number }}<br>
            <strong>Dated:</strong> {{ $invoice->invoice_date?->format('d-m-Y') ?? now()->format('d-m-Y') }}<br>
            <strong>Status:</strong> {{ ucfirst($invoice->status ?? 'draft') }}<br><br>

            <strong>Order:</strong> {{ $order->order_code ?? '—' }}<br>
            <strong>Order Date:</strong> {{ $order->order_date?->format('d-m-Y') ?? '—' }}<br>
            <strong>Delivery:</strong> {{ $order->expected_delivery_at?->format('d-m-Y') ?? '—' }}
        </td>
    </tr>
</table>

<table class="section">
    <tr>
        <th width="50%">Customer Address</th>
        <th width="50%">Shipping Address</th>
    </tr>
    <tr>
        <td>
            <strong>Name:</strong> {{ $order->customer_name }}<br>
            <strong>Mobile:</strong> {{ $order->customer_phone }}<br>
            {!! nl2br(e($order->billing_address)) !!}
        </td>
        <td>
            <strong>Name:</strong> {{ $order->customer_name }}<br>
            <strong>Mobile:</strong> {{ $order->customer_phone }}<br>
            {!! nl2br(e($order->shipping_address)) !!}
        </td>
    </tr>
</table>

<table class="items">
    <thead>
        <tr>
            <th width="5%">Sl.</th>
            <th width="30%">Description of Goods</th>
            <th width="10%">HSN/SAC</th>
            <th width="10%" class="right">Unit Cost</th>
            <th width="7%" class="right">Qty</th>
            <th width="8%" class="right">Tax</th>
            <th width="10%" class="right">Tax Amt</th>
            <th width="8%" class="right">Disc.</th>
            <th width="12%" class="right">Amount</th>
        </tr>
    </thead>
    <tbody>
        @php $i=1; @endphp
        @foreach($order->items as $item)
        <tr>
            <td class="center">{{ $i++ }}</td>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->hsn ?? '' }}</td>
            <td class="right">{{ number_format($item->price,2) }}</td>
            <td class="right">{{ $item->quantity }}</td>
            <td class="right">{{ number_format($item->tax_rate ?? 0,2) }}%</td>
            <td class="right">{{ number_format($item->tax_amount ?? 0,2) }}</td>
            <td class="right">{{ number_format($item->discount ?? 0,2) }}</td>
            <td class="right">{{ number_format($item->total,2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table class="totals">
    <tr>
        <td class="right" width="85%"><strong>Subtotal</strong></td>
        <td class="right" width="15%">{{ number_format($order->sub_total,2) }}</td>
    </tr>
    <tr>
        <td class="right"><strong>Other Charges</strong></td>
        <td class="right">{{ number_format($order->other_charges ?? 0,2) }}</td>
    </tr>
    <tr>
        <td class="right"><strong>Discount on All</strong></td>
        <td class="right">{{ number_format($order->discount_amount,2) }}</td>
    </tr>
    <tr>
        <td class="right"><strong>Grand Total</strong></td>
        <td class="right"><strong>{{ number_format($order->grand_total,2) }}</strong></td>
    </tr>
    <tr>
        <td class="right"><strong>Invoice Paid</strong></td>
        <td class="right">{{ number_format($order->paid_amount ?? 0,2) }}</td>
    </tr>
    <tr>
        <td class="right"><strong>Invoice Due</strong></td>
        <td class="right">{{ number_format($order->grand_total - ($order->paid_amount ?? 0),2) }}</td>
    </tr>
</table>

<div class="terms">
    <strong>Payment Details</strong><br>
    <strong>Status:</strong> {{ ucfirst($order->payment_status ?? 'unpaid') }}<br>
    <strong>Method:</strong> {{ $order->payment_method ?? '—' }}<br>
    <strong>Transaction ID:</strong> {{ $order->transaction_id ?? '—' }}<br>
    <strong>Paid At:</strong> {{ $order->paid_at?->format('d M Y H:i') ?? '—' }}
</div>

</body>
</html>
