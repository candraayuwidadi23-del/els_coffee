<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPendapatan = Order::sum('order_amount');
        $totalTransaksi = Order::count();
        $totalProduk = Product::count();
        
        // Ambil 5 transaksi terakhir
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('dashboard', compact('totalPendapatan', 'totalTransaksi', 'totalProduk', 'recentOrders'));
    
    }
}
