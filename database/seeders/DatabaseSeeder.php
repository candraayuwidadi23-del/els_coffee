<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $kasir = Role::firstOrCreate(['name' => 'kasir']);
        $pimpinan = Role::firstOrCreate(['name' => 'pimpinan']);

        // 2. Users
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['role_id' => $admin->id, 'name' => 'Admin Utama', 'password' => Hash::make('password')]
        );
        User::firstOrCreate(
            ['email' => 'kasir@gmail.com'],
            ['role_id' => $kasir->id, 'name' => 'Kasir Satu', 'password' => Hash::make('password')]
        );
        User::firstOrCreate(
            ['email' => 'pimpinan@gmail.com'],
            ['role_id' => $pimpinan->id, 'name' => 'Bapak Pimpinan', 'password' => Hash::make('password')]
        );

        // 3. Categories
        $c_coffee = Category::firstOrCreate(['category_name' => 'Coffee']);
        $c_non_coffee = Category::firstOrCreate(['category_name' => 'Non Coffee']);
        $c_tea = Category::firstOrCreate(['category_name' => 'Tea']);
        $c_food = Category::firstOrCreate(['category_name' => 'Food']);
        $c_snack = Category::firstOrCreate(['category_name' => 'Snack']);

        // 4. Products
        $products = [
            ['category_id' => $c_coffee->id, 'product_name' => 'Americano', 'product_price' => 20000, 'product_stock' => 50],
            ['category_id' => $c_coffee->id, 'product_name' => 'Espresso', 'product_price' => 15000, 'product_stock' => 50],
            ['category_id' => $c_coffee->id, 'product_name' => 'Cafe Latte', 'product_price' => 25000, 'product_stock' => 40],
            ['category_id' => $c_coffee->id, 'product_name' => 'Cappuccino', 'product_price' => 25000, 'product_stock' => 35],
            ['category_id' => $c_coffee->id, 'product_name' => 'Mocha', 'product_price' => 28000, 'product_stock' => 30],
            ['category_id' => $c_coffee->id, 'product_name' => 'Caramel Macchiato', 'product_price' => 30000, 'product_stock' => 25],
            ['category_id' => $c_coffee->id, 'product_name' => 'Vanilla Latte', 'product_price' => 28000, 'product_stock' => 30],
            ['category_id' => $c_tea->id, 'product_name' => 'Matcha Latte', 'product_price' => 25000, 'product_stock' => 45],
            ['category_id' => $c_tea->id, 'product_name' => 'Thai Tea', 'product_price' => 20000, 'product_stock' => 50],
            ['category_id' => $c_non_coffee->id, 'product_name' => 'Chocolate', 'product_price' => 22000, 'product_stock' => 40],
            ['category_id' => $c_snack->id, 'product_name' => 'Croissant', 'product_price' => 18000, 'product_stock' => 20],
            ['category_id' => $c_food->id, 'product_name' => 'Sandwich', 'product_price' => 25000, 'product_stock' => 15],
            ['category_id' => $c_snack->id, 'product_name' => 'French Fries', 'product_price' => 15000, 'product_stock' => 30],
            ['category_id' => $c_snack->id, 'product_name' => 'Cake', 'product_price' => 25000, 'product_stock' => 10],
            ['category_id' => $c_snack->id, 'product_name' => 'Donut', 'product_price' => 10000, 'product_stock' => 50],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(
                ['product_name' => $p['product_name']], 
                $p
            );
        }
    }
}