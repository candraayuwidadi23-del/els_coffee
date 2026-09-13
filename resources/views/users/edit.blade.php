@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-3 pb-2">
                <h5 class="card-title mb-0">Edit Pengguna: {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password (Opsional)</label>
                        <input type="password" name="password" class="form-control" minlength="6" placeholder="Ketik di sini jika ingin ubah password">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Peran Utama (Role)</label>
                        <select name="role_id" class="form-select" required>
                            <option value="">Pilih Role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- BAGIAN HAK AKSES SPESIFIK -->
                    <hr class="my-4">
                    <h6 class="fw-bold mb-3">Atur Izin Halaman Khusus</h6>
                    <div class="row mb-4">
                        @php $perms = $user->permissions ?? []; @endphp
                        
                        <div class="col-md-6 mb-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="dashboard" {{ in_array('dashboard', $perms) ? 'checked' : '' }}>
                                <label class="form-check-label">Lihat Dashboard</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="pengguna" {{ in_array('pengguna', $perms) ? 'checked' : '' }}>
                                <label class="form-check-label">Kelola Pengguna</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="kategori" {{ in_array('kategori', $perms) ? 'checked' : '' }}>
                                <label class="form-check-label">Kelola Kategori</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="produk" {{ in_array('produk', $perms) ? 'checked' : '' }}>
                                <label class="form-check-label">Kelola Produk</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="pos" {{ in_array('pos', $perms) ? 'checked' : '' }}>
                                <label class="form-check-label">Akses Mesin Kasir (POS)</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="transaksi" {{ in_array('transaksi', $perms) ? 'checked' : '' }}>
                                <label class="form-check-label">Lihat Riwayat Transaksi</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-coffee">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection