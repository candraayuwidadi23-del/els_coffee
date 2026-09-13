@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $o)
                <tr>
                    <td><span class="fw-bold">{{ $o->order_code }}</span></td>
                    <td>{{ date('d-m-Y H:i', strtotime($o->order_date)) }}</td>
                    <td>{{ $o->user->name }}</td>
                    <td>Rp {{ number_format($o->order_amount, 0, ',', '.') }}</td>
                    <td><span class="badge bg-secondary">{{ strtoupper($o->payment_method) }}</span></td>
                    <td>
                        <a href="{{ route('orders.show', $o->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i> Detail</a>
                        <a href="{{ route('orders.receipt', $o->id) }}" target="_blank" class="btn btn-sm btn-dark"><i class="bi bi-printer"></i> Struk</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection