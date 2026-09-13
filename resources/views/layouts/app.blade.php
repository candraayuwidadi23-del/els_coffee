<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', "el'sCoffe POS")</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
    :root {
        --dark-pink: #C2185B; /* Menggantikan warna dark-brown */
        --pink: #FF69B4;      /* Menggantikan warna coffee (HotPink) */
        --light-pink: #FFF0F5; /* Menggantikan warna cream (LavenderBlush) */
    }
    body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    
    .sidebar { background-color: var(--dark-pink); min-height: 100vh; transition: all 0.3s; }
    .sidebar .nav-link { color: var(--light-pink); margin-bottom: 5px; border-radius: 5px; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: var(--pink); color: white; }
    
    .navbar-custom { background-color: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    
    /* Catatan: Nama class tetap 'btn-coffee' agar kode HTML/tombol kamu tidak error, tapi warnanya sudah berubah jadi pink */
    .btn-coffee { background-color: var(--pink); color: white; border: none; }
    .btn-coffee:hover { background-color: var(--dark-pink); color: white; }
</style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3" style="width: 250px;">
            <h4 class="text-white text-center mb-4 mt-2 fw-bold">el'sCoffe</h4>
            <hr class="text-white">
            <ul class="nav flex-column">
    @php $perms = Auth::user()->permissions ?? []; @endphp

    <!-- Dashboard -->
    @if(in_array('dashboard', $perms))
    <li class="nav-item">
        <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
    </li>
    @endif

    <!-- Pengguna -->
    @if(in_array('pengguna', $perms))
    <li class="nav-item">
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i> Pengguna
        </a>
    </li>
    @endif

    <!-- Kategori -->
    @if(in_array('kategori', $perms))
    <li class="nav-item">
        <a href="/categories" class="nav-link {{ request()->is('categories*') ? 'active' : '' }}">
            <i class="bi bi-tags me-2"></i> Kategori
        </a>
    </li>
    @endif

    <!-- Produk -->
    @if(in_array('produk', $perms))
    <li class="nav-item">
        <a href="/products" class="nav-link {{ request()->is('products*') ? 'active' : '' }}">
            <i class="bi bi-box-seam me-2"></i> Produk
        </a>
    </li>
    @endif

    <!-- POS / Kasir -->
    @if(in_array('pos', $perms))
    <li class="nav-item">
        <a href="/pos" class="nav-link {{ request()->is('pos') ? 'active' : '' }}">
            <i class="bi bi-cart me-2"></i> POS / Kasir
        </a>
    </li>
    @endif

    <!-- Transaksi -->
    @if(in_array('transaksi', $perms))
    <li class="nav-item">
        <a href="/orders" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}">
            <i class="bi bi-receipt me-2"></i> Transaksi
        </a>
    </li>
    @endif
</ul>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 overflow-auto" style="height: 100vh;">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-custom px-4 py-3">
                <div class="container-fluid">
                    <h5 class="mb-0 text-muted">@yield('title', 'Dashboard')</h5>
                    <div class="ms-auto d-flex align-items-center">
                        <span class="me-3 fw-semibold" style="color: var(--dark-brown);">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }} ({{ strtoupper(Auth::user()->role->name) }})
                        </span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
    <script>
    Swal.fire({
        title: 'Berhasil!',
        text: '{!! session('success') !!}',
        icon: 'success',
        @if(session('print_order_id'))
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-printer"></i> Cetak Struk',
        cancelButtonText: 'Lanjut Transaksi',
        confirmButtonColor: '#6F4E37'
        @endif
    }).then((result) => {
        @if(session('print_order_id'))
        if (result.isConfirmed) {
            window.open("{{ url('/orders') }}/{{ session('print_order_id') }}/receipt", "_blank");
        }
        @endif
    });
    </script>
    @endif
    @if(session('error'))
    <script>
        Swal.fire('Gagal!', '{{ session('error') }}', 'error');
    </script>
    @endif
    @stack('scripts')
</body>
</html>