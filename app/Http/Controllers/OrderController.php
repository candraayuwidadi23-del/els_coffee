<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'orderDetails.product'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function receipt($id)
    {
        $order = Order::with(['user', 'orderDetails.product'])->findOrFail($id);
        return view('orders.receipt', compact('order'));
    }
}