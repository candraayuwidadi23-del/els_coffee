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
    $cart = json_decode($request->cart, true);
    $total = 0;

    foreach($cart as $item) {
        $total += $item['price'] * $item['qty'];
        }

    DB::beginTransaction();
    try {
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_code' => 'INV-' . time(),
            'order_date' => now(),
            'order_subtotal' => $total,
            'order_amount' => $total,
            'order_paid' => $request->order_paid,
            'order_change' => $request->order_paid - $total,
            'payment_method' => $request->payment_method,
        ]);

        foreach($cart as $item) {
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
    'success' => 'Transaksi berhasil! Kembalian: Rp ' . number_format($order->order_change, 0, ',', '.'),
    'print_order_id' => $order->id // Mengirim ID transaksi ke halaman depan
        ]);

        } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
