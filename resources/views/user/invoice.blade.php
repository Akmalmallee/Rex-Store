<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->invoice_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; margin: 0; padding: 40px; }
        .header { border-bottom: 3px solid #C8A951; padding-bottom: 20px; margin-bottom: 30px; }
        .header .brand { font-size: 28px; font-weight: bold; color: #000; }
        .header .title { font-size: 22px; color: #C8A951; margin-top: 5px; }
        .info-section { margin-bottom: 30px; }
        .info-section table { width: 100%; }
        .info-section td { vertical-align: top; width: 50%; }
        .info-section .label { font-size: 10px; color: #999; text-transform: uppercase; letter-spacing: 1px; }
        .info-section .value { font-size: 13px; font-weight: bold; margin-bottom: 10px; }
        .info-section .value-light { font-size: 12px; color: #666; margin-bottom: 8px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.items th { background: #f5f5f5; padding: 10px 12px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #666; }
        table.items td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 12px; }
        table.items td.right { text-align: right; }
        table.items td.center { text-align: center; }
        .summary { width: 300px; margin-left: auto; }
        .summary table { width: 100%; }
        .summary td { padding: 6px 0; font-size: 12px; }
        .summary td.right { text-align: right; }
        .summary .total td { padding-top: 10px; border-top: 2px solid #333; font-size: 16px; font-weight: bold; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; font-size: 11px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">REXFASHION</div>
        <div class="title">INVOICE</div>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td>
                    <div class="label">Invoice Number</div>
                    <div class="value">{{ $order->invoice_number }}</div>
                    <div class="label">Order Date</div>
                    <div class="value-light">{{ $order->created_at->format('d M Y H:i') }}</div>
                    <div class="label">Payment Method</div>
                    <div class="value-light">{{ $order->payment_method }}</div>
                    <div class="label">Status</div>
                    <div class="value-light">{{ ucfirst($order->status) }}</div>
                </td>
                <td>
                    <div class="label">Customer</div>
                    <div class="value">{{ $order->user->name ?? 'N/A' }}</div>
                    <div class="value-light">{{ $order->user->email ?? '' }}</div>
                    <div class="value-light">{{ $order->phone }}</div>
                    <div class="label">Shipping Address</div>
                    <div class="value-light">{{ $order->address }}, {{ $order->city }}</div>
                    <div class="value-light">{{ $order->shipping_courier }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Product</th>
                <th class="center">Size</th>
                <th class="center">Color</th>
                <th class="center">Qty</th>
                <th class="right">Price</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td class="center">{{ $item->size ?? '-' }}</td>
                <td class="center">{{ $item->color ?? '-' }}</td>
                <td class="center">{{ $item->quantity }}</td>
                <td class="right">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Shipping</td>
                <td class="right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
            </tr>
            @if($order->discount > 0)
            <tr>
                <td>Discount</td>
                <td class="right" style="color: #16a34a;">-Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total">
                <td>Total</td>
                <td class="right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Thank you for your purchase!</p>
        <p>Rex Fashion - Elevate Your Style</p>
    </div>
</body>
</html>