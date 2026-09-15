<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::where('is_active', true)->where('product_stock', '>', 0);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get();
        return view('pos.index', compact('products', 'categories'));
    }

    public function checkout(Request $request)
    {
    // Validasi input pembayaran
    $request->validate([
        'cart' => 'required',
        'order_paid' => 'required|numeric|min:0',
        'payment_method' => 'required|string',
    ]);

    $cart = json_decode($request->cart, true);

    if (empty($cart)) {
        return back()->with('error', 'Keranjang belanja masih kosong.');
    }

    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['qty'];
    }

    // Hitung total akhir termasuk pajak 10% (sesuaikan jika pajak berbeda)
    $tax = $subtotal * 0.1;
    $grandTotal = $subtotal + $tax;

    // Validasi apakah uang yang dibayar mencukupi
    if ($request->order_paid < $grandTotal) {
        return back()->with('error', 'Uang pembayaran kurang dari total belanja!');
    }

    // Hitung kembalian yang akurat berdasarkan grandTotal
    $change = $request->order_paid - $grandTotal;

    DB::beginTransaction();
    try {
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_code' => 'INV-' . time(),
            'order_date' => now(),
            'order_subtotal' => $subtotal,
            'order_amount' => $grandTotal,
            'order_paid' => $request->order_paid,
            'order_change' => $change, // Menggunakan variabel change yang sudah akurat
            'payment_method' => $request->payment_method,
        ]);

        foreach ($cart as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'order_quantity' => $item['qty'],
                'order_price' => $item['price'],
                'order_subtotal' => $item['price'] * $item['qty']
            ]);

            // Pengurangan stok otomatis
            Product::where('id', $item['id'])->decrement('product_stock', $item['qty']);
        }

        DB::commit();

        return redirect()->route('pos.index')->with([
            'success' => 'Transaksi berhasil! Kembalian: Rp ' . number_format($change, 0, ',', '.'),
            'print_order_id' => $order->id
        ]);
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
}
