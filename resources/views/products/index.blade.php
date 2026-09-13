@extends('layouts.app')
@section('title', 'Manajemen Produk')

@section('content')
<div class="row">
    <!-- Form Tambah Produk -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-3">Tambah Produk</h5>
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Pilih Kategori...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" name="product_price" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="product_stock" class="form-control" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Produk</label>
                        <input type="file" name="product_photo" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-coffee w-100">Simpan Produk</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Daftar Produk -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Foto</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $p)
                        <tr>
                            <td>
                                @if($p->product_photo)
                                    <img src="{{ asset('storage/'.$p->product_photo) }}" width="45" height="45" class="rounded object-fit-cover">
                                @else
                                    <div class="bg-secondary text-white rounded text-center d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $p->product_name }}</td>
                            <td>{{ $p->category->category_name }}</td>
                            <td>Rp {{ number_format($p->product_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $p->product_stock == 0 ? 'bg-danger' : ($p->product_stock <= 5 ? 'bg-warning text-dark' : 'bg-success') }}">
                                    {{ $p->product_stock }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $p->is_active ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <!-- Tombol Trigger Modal Edit -->
                                    <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editModal{{ $p->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>

                                <!-- Modal Edit Produk -->
                                <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('products.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Kategori</label>
                                                        <select name="category_id" class="form-select" required>
                                                            @foreach($categories as $cat)
                                                                <option value="{{ $cat->id }}" {{ $p->category_id == $cat->id ? 'selected' : '' }}>
                                                                    {{ $cat->category_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Produk</label>
                                                        <input type="text" name="product_name" class="form-control" value="{{ $p->product_name }}" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Harga (Rp)</label>
                                                            <input type="number" name="product_price" class="form-control" value="{{ $p->product_price }}" min="0" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Stok</label>
                                                            <input type="number" name="product_stock" class="form-control" value="{{ $p->product_stock }}" min="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Foto Baru (Opsional)</label>
                                                        <input type="file" name="product_photo" class="form-control" accept="image/*">
                                                    </div>
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch{{ $p->id }}" {{ $p->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="activeSwitch{{ $p->id }}">Produk Aktif (Tampil di POS)</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-coffee">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection