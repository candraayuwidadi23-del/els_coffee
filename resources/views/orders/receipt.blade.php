<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $order->order_code }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 300px; margin: auto; padding: 10px; }
        .text-center { text-align: center; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        .flex { display: flex; justify-content: space-between; }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <h3 style="margin:0;">el'sCoffe</h3>
        <p style="margin:2px 0;">Coffee & Good Mood</p>
    </div>
    <div class="line"></div>
    <div>Invoice : {{ $order->order_code }}</div>
    <div>Tanggal : {{ date('d/m/Y H:i', strtotime($order->order_date)) }}</div>
    <div>Kasir   : {{ $order->user->name }}</div>
    <div class="line"></div>
    @foreach($order->orderDetails as $d)
        <div>{{ $d->product->product_name }}</div>
        <div class="flex">
            <span>{{ $d->order_quantity }} x {{ number_format($d->order_price, 0, ',', '.') }}</span>
            <span>{{ number_format($d->order_subtotal, 0, ',', '.') }}</span>
        </div>
    @endforeach
    <div class="line"></div>
    <div class="flex"><strong>TOTAL</strong> <strong>Rp {{ number_format($order->order_amount, 0, ',', '.') }}</strong></div>
    <div class="flex">Bayar <span>Rp {{ number_format($order->order_paid, 0, ',', '.') }}</span></div>
    <div class="flex">Kembali <span>Rp {{ number_format($order->order_change, 0, ',', '.') }}</span></div>
    <div class="flex">Metode <span>{{ strtoupper($order->payment_method) }}</span></div>
    <div class="line"></div>
    <div class="text-center">
        <p>Terima Kasih Atas Kunjungannya!<br>el'sCoffe</p>
    </div>
</body>
</html>