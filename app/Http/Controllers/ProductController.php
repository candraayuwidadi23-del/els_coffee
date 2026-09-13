<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'product_name' => 'required|string|max:255',
            'product_price' => 'required|numeric',
            'product_stock' => 'required|numeric',
            'product_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('product_photo')) {
            $data['product_photo'] = $request->file('product_photo')->store('products', 'public');
        }

        Product::create($data);
        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
    $request->validate([
        'category_id' => 'required',
        'product_name' => 'required|string|max:255',
        'product_price' => 'required|numeric|min:0',
        'product_stock' => 'required|integer|min:0',
        'product_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $data = $request->except(['product_photo']);

    // Jika user mengunggah foto baru
    if ($request->hasFile('product_photo')) {
        // Hapus foto lama jika ada
        if ($product->product_photo) {
            Storage::disk('public')->delete($product->product_photo);
        }
        $data['product_photo'] = $request->file('product_photo')->store('products', 'public');
    }

    $data['is_active'] = $request->has('is_active') ? true : false;

    $product->update($data);

    return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->product_photo) {
            Storage::disk('public')->delete($product->product_photo);
        }
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
