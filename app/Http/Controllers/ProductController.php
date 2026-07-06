<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. HALAMAN INDEX: Menampilkan Semua Produk
    public function index()
    {
        // Hanya memuat relasi category karena data stock ada di tabel products itu sendiri
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    // 2. HALAMAN CREATE: Form Tambah Produk
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // 3. PROSES STORE: Menyimpan Data Produk Baru
// 3. PROSES STORE: Menyimpan Data Produk Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'           => 'required|string|unique:products,code',
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'min_stock'      => 'required|integer|min:0',
            'max_stock'      => 'required|integer|min:0',
            'image_url'      => 'nullable|url',
        ]);

        // Ambil image_url terlebih dahulu, lalu hapus dari array validated
        $imageUrl = $validated['image_url'] ?? null;
        unset($validated['image_url']);

        // Simpan data produk bersih tanpa kolom image_url ke database
        $product = Product::create($validated);

        // Jika user mengisi link gambar, perintahkan Spatie menyimpannya ke tabel media terpisah
        if ($imageUrl) {
            $product->addMediaFromUrl($imageUrl)->toMediaCollection('product_images');
        }

        return redirect()->route('products.index')->with('success', '🎉 Produk baru berhasil ditambahkan!');
    }

    // 4. HALAMAN EDIT: Menampilkan Form Edit
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

// 5. PROSES UPDATE: Menyimpan Perubahan Data Produk
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code'           => 'required|string|unique:products,code,' . $product->id,
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'min_stock'      => 'required|integer|min:0',
            'max_stock'      => 'required|integer|min:0',
            'image_url'      => 'nullable|url',
        ]);

        // Ambil image_url terlebih dahulu, lalu hapus dari array validated
        $imageUrl = $validated['image_url'] ?? null;
        unset($validated['image_url']);

        // Perbarui data utama produk ke tabel products
        $product->update($validated);

        // Jika user mengirimkan URL baru
        if ($imageUrl) {
            // Hapus gambar lama yang tersimpan di Spatie agar tidak menumpuk
            $product->clearMediaCollection('product_images');
            
            // Simpan gambar baru lewat Spatie
            $product->addMediaFromUrl($imageUrl)->toMediaCollection('product_images');
        }

        return redirect()->route('products.index')->with('success', '✏️ Data produk berhasil diperbarui!');
    }

    // 6. PROSES DESTROY: Menghapus Produk
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', '🗑️ Produk berhasil dihapus!');
    }
}