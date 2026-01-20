<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            margin-bottom: 20px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        .box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f5f5f5;
        }

        .right {
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="title">Invoice</div>
    <div>Invoice No: <strong>{{ $invoice->invoice_number }}</strong></div>
    <div>Date: {{ $invoice->created_at->format('d M Y') }}</div>
</div>

<div class="box">
    <strong>Bill To:</strong><br>
    {{ $order->customer_name }}<br>
    {!! nl2br(e($order->billing_address)) !!}
</div>

<table>
    <thead>
        <tr>
            <th>Product</th>
            <th class="right">Qty</th>
            <th class="right">Price</th>
            <th class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td class="right">{{ $item->quantity }}</td>
                <td class="right">{{ number_format($item->price, 2) }}</td>
                <td class="right">{{ number_format($item->total, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="right">Grand Total</th>
            <th class="right">{{ number_format($order->grand_total, 2) }}</th>
        </tr>
    </tfoot>
</table>

<div class="footer">
    This is a system generated invoice.
</div>

</body>
</html>
