@extends('layouts.app')
@section('title', 'Point of Sales (Kasir)')

@section('content')
<div class="row h-100">
    <!-- Area Produk -->
    <div class="col-md-8">
        <!-- Filter Kategori -->
        <div class="d-flex mb-3 gap-2 overflow-auto pb-2">
            <a href="{{ route('pos.index') }}" class="btn btn-{{ !request('category_id') ? 'coffee' : 'outline-secondary' }}">Semua</a>
            @foreach($categories as $cat)
                <a href="{{ route('pos.index', ['category_id' => $cat->id]) }}" 
                   class="btn btn-{{ request('category_id') == $cat->id ? 'coffee' : 'outline-secondary' }}">
                    {{ $cat->category_name }}
                </a>
            @endforeach
        </div>

        <!-- Card Produk -->
        <div class="row g-3">
            @forelse($products as $p)
            <div class="col-md-3 col-6">
                <div class="card h-100 shadow-sm border-0 cursor-pointer product-card" 
                     onclick="addToCart({{ $p->id }}, '{{ $p->product_name }}', {{ $p->product_price }})">
                    <div class="card-body text-center p-2">
                        <!-- TAMPILAN FOTO PRODUK -->
                        <div class="bg-light rounded mb-2 overflow-hidden d-flex align-items-center justify-content-center" style="height: 110px;">
                            @if($p->product_photo)
                                <img src="{{ asset('storage/' . $p->product_photo) }}" alt="{{ $p->product_name }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <i class="bi bi-cup-hot fs-1 text-muted"></i>
                            @endif
                        </div>
                        <h6 class="mb-1 text-truncate fw-bold text-dark" style="font-size: 0.9rem;">{{ $p->product_name }}</h6>
                        <span class="text-danger fw-bold d-block">Rp {{ number_format($p->product_price, 0, ',', '.') }}</span>
                        <small class="text-muted" style="font-size: 0.75rem;">Stok: {{ $p->product_stock }}</small>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                Tidak ada produk tersedia di kategori ini.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Area Keranjang -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-cart3 me-2"></i>Pesanan Saat Ini</h5>
            </div>
            <div class="card-body p-0 overflow-auto" style="max-height: 50vh;">
                <table class="table table-borderless mb-0">
                    <tbody id="cart-items">
                        <tr><td colspan="4" class="text-center py-4 text-muted">Keranjang masih kosong</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white p-3 border-top">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold">Total</span>
                    <h4 class="fw-bold text-danger mb-0" id="cart-total">Rp 0</h4>
                </div>
                <button class="btn btn-coffee w-100 py-2 fs-5" onclick="processCheckout()">Bayar Sekarang</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let cart = [];

    function addToCart(id, name, price) {
        let itemIndex = cart.findIndex(i => i.id === id);
        if (itemIndex !== -1) {
            cart[itemIndex].qty++;
        } else {
            cart.push({ id: id, name: name, price: price, qty: 1 });
        }
        renderCart();
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: name + ' ditambahkan', showConfirmButton: false, timer: 800 });
    }

    function renderCart() {
        let html = '';
        let total = 0;
        
        if (cart.length === 0) {
            html = '<tr><td colspan="4" class="text-center py-4 text-muted">Keranjang masih kosong</td></tr>';
        } else {
            cart.forEach((item, index) => {
                let subtotal = item.price * item.qty;
                total += subtotal;
                html += `
                <tr class="align-middle">
                    <td><div class="fw-semibold" style="font-size: 0.9rem;">${item.name}</div><div class="text-muted" style="font-size: 0.8rem;">Rp ${item.price.toLocaleString('id-ID')}</div></td>
                    <td style="width: 70px;"><input type="number" class="form-control form-control-sm text-center" value="${item.qty}" min="1" onchange="updateQty(${index}, this.value)"></td>
                    <td class="text-end fw-semibold" style="font-size: 0.9rem;">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    <td class="text-end"><button class="btn btn-sm btn-outline-danger border-0" onclick="removeItem(${index})"><i class="bi bi-x-lg"></i></button></td>
                </tr>`;
            });
        }
        
        document.getElementById('cart-items').innerHTML = html;
        document.getElementById('cart-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    function updateQty(index, qty) {
        if(qty < 1) return removeItem(index);
        cart[index].qty = parseInt(qty);
        renderCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function processCheckout() {
    if(cart.length === 0) return Swal.fire('Oops', 'Keranjang kosong!', 'error');

    let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

    Swal.fire({
        title: 'Proses Pembayaran',
        html: `
            <h4 class="mb-3 text-danger fw-bold">Total: Rp ${total.toLocaleString('id-ID')}</h4>
            
            <select id="payment_method" class="form-select mb-3" onchange="toggleQRIS()">
                <option value="cash">Tunai (Cash)</option>
                <option value="qris">QRIS</option>
            </select>
            
            <!-- Area QRIS (Disembunyikan secara default) -->
            <div id="qris-container" class="mb-3 d-none">
                <p class="text-muted small mb-1">Scan QR Code di bawah ini:</p>
                <!-- Ganti URL 'src' dengan path gambar QRIS asli toko Anda -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Pembayaran+QRIS+ElCoffe+Rp${total}" alt="QRIS" class="img-fluid rounded border p-2 mb-2">
            </div>
            
            <input type="number" id="amount_paid" class="form-control form-control-lg text-center" placeholder="Nominal Uang Diterima" value="${total}">
        `,
        showCancelButton: true,
        confirmButtonText: 'Bayar & Simpan',
        confirmButtonColor: '#6F4E37',
        didOpen: () => {
            // Fungsi untuk menampilkan/menyembunyikan QR Code
            window.toggleQRIS = () => {
                let method = document.getElementById('payment_method').value;
                let qrisContainer = document.getElementById('qris-container');
                let amountInput = document.getElementById('amount_paid');
                
                if (method === 'qris') {
                    qrisContainer.classList.remove('d-none');
                    amountInput.value = total; // Uang pas
                    amountInput.readOnly = true; // Kunci input nominal
                } else {
                    qrisContainer.classList.add('d-none');
                    amountInput.readOnly = false; // Buka input nominal
                }
            };
        },
        preConfirm: () => {
            let method = document.getElementById('payment_method').value;
            let paid = document.getElementById('amount_paid').value;
            if (parseInt(paid) < total) Swal.showValidationMessage('Uang tidak mencukupi!');
            return { method, paid };
        }
    }).then((result) => {
        if (result.isConfirmed) submitOrder(result.value.method, result.value.paid);
    });
}

    function submitOrder(method, paid) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("pos.checkout") }}';
        form.innerHTML = `
            @csrf
            <input type="hidden" name="cart" value='${JSON.stringify(cart)}'>
            <input type="hidden" name="payment_method" value="${method}">
            <input type="hidden" name="order_paid" value="${paid}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
</script>
<style>
    .cursor-pointer { cursor: pointer; }
    .product-card:hover { transform: translateY(-3px); transition: 0.2s; border: 1px solid var(--coffee) !important; }
</style>
@endpush
@endsection