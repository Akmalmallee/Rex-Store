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
        .payment-info { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 11px; color: #666; }
        .status-badge { display: inline-block; padding: 4px 14px; border-radius: 12px; font-size: 11px; font-weight: bold; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-paid, .status-success, .status-completed { background: #d4edda; color: #155724; }
        .status-process, .status-shipped { background: #cce5ff; color: #004085; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 11px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width:100%;">
            <tr>
                <td>
                    <div class="brand">REX FASHION</div>
                    <div class="title">INVOICE</div>
                </td>
                <td style="text-align:right;">
                    <div style="font-size:11px;color:#666;">
                        {{ config('app.url') ?? 'rex-fashion.com' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td>
                    <div class="label">Invoice No</div>
                    <div class="value">{{ $order->invoice_number }}</div>
                    <div class="label">Order Date</div>
                    <div class="value-light">{{ $order->created_at->format('d M Y H:i') }}</div>
                    <div class="label">Status</div>
                    <div><span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></div>
                </td>
                <td>
                    <div class="label">Bill To</div>
                    <div class="value">{{ $order->user->name ?? '-' }}</div>
                    <div class="value-light">{{ $order->user->email ?? '-' }}</div>
                    <div class="value-light">{{ $order->address }}</div>
                    <div class="value-light">{{ $order->city }}</div>
                    <div class="value-light">Phone: {{ $order->phone }}</div>
                    @if($order->shipping_courier)
                    <div class="value-light">Courier: {{ $order->shipping_courier }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="width:40%;">Product</th>
                <th style="width:15%;">Size</th>
                <th style="width:15%;">Color</th>
                <th class="right" style="width:10%;">Qty</th>
                <th class="right" style="width:10%;">Price</th>
                <th class="right" style="width:10%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->size ?? '-' }}</td>
                <td>{{ $item->color ?? '-' }}</td>
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
                <td class="right" style="color:#C8A951;">-Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total">
                <td>Grand Total</td>
                <td class="right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="payment-info">
        <table style="width:100%;">
            <tr>
                <td style="width:50%;">
                    <strong>Payment Method:</strong> {{ $order->payment_method }}
                </td>
                <td style="width:50%;">
                    <strong>Payment Status:</strong>
                    <span class="status-badge status-{{ $order->payment_status == 'success' ? 'completed' : ($order->payment_status == 'failed' ? 'cancelled' : 'pending') }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </td>
            </tr>
        </table>
        @if($order->notes)
        <p style="margin-top:10px;"><strong>Notes:</strong> {{ $order->notes }}</p>
        @endif
    </div>

    <div class="footer">
        Terima kasih telah berbelanja di Rex Fashion
    </div>
</body>
</html>
