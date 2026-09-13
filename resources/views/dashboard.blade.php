@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-primary text-white h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-wallet2"></i> Total Pendapatan</h6>
                <h3 class="fw-bold mt-3">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-success text-white h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-receipt"></i> Total Transaksi</h6>
                <h3 class="fw-bold mt-3">{{ $totalTransaksi }} Pesanan</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-warning text-dark h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-box-seam"></i> Total Produk</h6>
                <h3 class="fw-bold mt-3">{{ $totalProduk }} Item</h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold">Transaksi Terbaru</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Invoice</th>
                    <th>Waktu</th>
                    <th>Kasir</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td class="ps-3 fw-semibold">{{ $order->order_code }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->order_date)->diffForHumans() }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td class="text-danger fw-bold">Rp {{ number_format($order->order_amount, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection