<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        .muted {
            color: #666;
            font-size: 11px;
        }

        .box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 14px;
        }

        .box-title {
            font-weight: bold;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #f5f5f5;
        }

        .right {
            text-align: right;
        }

        .totals {
            width: 40%;
            margin-left: auto;
            margin-top: 12px;
        }

        .totals td {
            border: none;
            padding: 4px 6px;
        }

        .totals .label {
            text-align: right;
        }

        .totals .value {
            text-align: right;
            width: 120px;
        }

        .totals .grand {
            border-top: 1px solid #000;
            font-weight: bold;
        }
    </style>
</head>
<body onload="window.print()">

<div class="header">
    <div>
        <div class="title">INVOICE</div>
        <div class="muted">Invoice No: {{ $invoice->invoice_number }}</div>
        <div class="muted">
            Date: {{ $invoice->invoice_date?->format('d M Y') ?? $invoice->created_at?->format('d M Y') ?? '—' }}
        </div>
        <div class="muted">Status: {{ ucfirst($invoice->status ?? 'draft') }}</div>
    </div>

    <div class="right">
        <strong>Order:</strong> {{ $order->order_code ?? '—' }}<br>
        <span class="muted">Order Date: {{ $order->order_date?->format('d M Y') ?? '—' }}</span><br>
        <span class="muted">Expected Delivery: {{ $order->expected_delivery_at?->format('d M Y') ?? '—' }}</span>
    </div>
</div>

<div class="box">
    <div class="box-title">Bill To</div>
    <strong>{{ $order->customer_name ?? '—' }}</strong><br>
    {{ $order->customer_email ?? '—' }}<br>
    {{ $order->customer_phone ?? '—' }}<br>
    {!! nl2br(e($order->billing_address ?? '—')) !!}
</div>

<div class="box">
    <div class="box-title">Ship To</div>
    {!! nl2br(e($order->shipping_address ?? '—')) !!}
</div>

<table>
    <thead>
        <tr>
            <th style="width: 45%;">Product</th>
            <th class="right" style="width: 15%;">Qty</th>
            <th class="right" style="width: 20%;">Price</th>
            <th class="right" style="width: 20%;">Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($order->items as $item)
            <tr>
                <td>{{ $item->product_name ?? '—' }}</td>
                <td class="right">{{ $item->quantity ?? 0 }}</td>
                <td class="right">{{ number_format($item->price ?? 0, 2) }}</td>
                <td class="right">{{ number_format($item->total ?? 0, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="right">No items found</td>
            </tr>
        @endforelse
    </tbody>
</table>

<table class="totals">
    <tr>
        <td class="label">Sub Total</td>
        <td class="value">{{ number_format($order->sub_total ?? 0, 2) }}</td>
    </tr>
    <tr>
        <td class="label">Tax</td>
        <td class="value">{{ number_format($order->tax_amount ?? 0, 2) }}</td>
    </tr>
    <tr>
        <td class="label">Shipping</td>
        <td class="value">{{ number_format($order->shipping_amount ?? 0, 2) }}</td>
    </tr>
    <tr>
        <td class="label">Discount</td>
        <td class="value">-{{ number_format($order->discount_amount ?? 0, 2) }}</td>
    </tr>
    <tr class="grand">
        <td class="label"><strong>Grand Total</strong></td>
        <td class="value"><strong>{{ number_format($order->grand_total ?? 0, 2) }}</strong></td>
    </tr>
</table>

<div class="box" style="margin-top: 14px;">
    <div class="box-title">Payment Details</div>
    <strong>Status:</strong> {{ ucfirst($order->payment_status ?? 'unpaid') }}<br>
    <strong>Method:</strong> {{ $order->payment_method ?? '—' }}<br>
    <strong>Transaction ID:</strong> {{ $order->transaction_id ?? '—' }}<br>
    <strong>Paid At:</strong> {{ $order->paid_at?->format('d M Y H:i') ?? '—' }}
</div>

</body>
</html>
