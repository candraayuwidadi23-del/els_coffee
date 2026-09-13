@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row g-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white border-0 shadow-sm">
            <div class="card-body py-4">
                <h6 class="mb-2">Total Produk</h6>
                <h3 class="mb-0">{{ $totalProduk }} Item</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white border-0 shadow-sm">
            <div class="card-body py-4">
                <h6 class="mb-2">Total Kategori</h6>
                <h3 class="mb-0">{{ $totalKategori }} Kategori</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark border-0 shadow-sm">
            <div class="card-body py-4">
                <h6 class="mb-2">Transaksi Hari Ini</h6>
                <h3 class="mb-0">{{ $transaksiHariIni }} Trx</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-dark border-0 shadow-sm">
            <div class="card-body py-4">
                <h6 class="mb-2">Penjualan Hari Ini</h6>
                <h3 class="mb-0">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection