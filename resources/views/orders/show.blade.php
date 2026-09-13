@extends('layouts.app')
@section('title', 'Detail Transaksi')

@section('content')
<div class="card shadow-sm border-0 max-w-2xl mx-auto">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Invoice: {{ $order->order_code }}</h5>
        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-sm-6">
                <div class="text-muted mb-1">Tanggal Transaksi</div>
                <div class="fw-semibold">{{ date('d M Y, H:i', strtotime($order->order_date)) }}</div>
            </div>
            <div class="col-sm-6 text-sm-end">
                <div class="text-muted mb-1">Kasir</div>
                <div class="fw-semibold">{{ $order->user->name }}</div>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderDetails as $detail)
                <tr>
                    <td>{{ $detail->product->product_name }}</td>
                    <td class="text-center">{{ $detail->order_quantity }}</td>
                    <td class="text-end">Rp {{ number_format($detail->order_price, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($detail->order_subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total Belanja</th>
                    <th class="text-end text-danger fs-5">Rp {{ number_format($order->order_amount, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Tunai ({{ strtoupper($order->payment_method) }})</th>
                    <th class="text-end">Rp {{ number_format($order->order_paid, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Kembalian</th>
                    <th class="text-end">Rp {{ number_format($order->order_change, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
        
        <div class="text-end mt-3">
            <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" class="btn btn-dark">
                <i class="bi bi-printer"></i> Cetak Struk
            </a>
        </div>
    </div>
</div>
@endsection