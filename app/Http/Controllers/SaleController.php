<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    // Tampilkan Riwayat Transaksi Penjualan
    public function index() {
        $sales = Sale::with('user')->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    // Tampilkan Halaman Antarmuka Kasir (POS)
    public function create() {
        // Hanya mengambil produk yang stoknya di atas 0
        $products = Product::where('stock', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    // Proses Simpan Transaksi Penjualan Kasir
    public function store(Request $request) {
        $request->validate([
            'payment_method' => 'required|in:Cash,Debit,Credit,Transfer Bank',
            'paid_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $subtotal = 0;
            $itemsToProcess = [];

            // 1. VALIDASI STOK REAL-TIME & HITUNG SUBTOTAL
            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                // Cek apakah stok digudang mencukupi
                if ($product->stock < $itemData['quantity']) {
                    return redirect()->back()->withErrors([
                        'items' => "Stok untuk produk '{$product->name}' tidak mencukupi! Sisa stok: {$product->stock}"
                    ])->withInput();
                }

                $itemSubtotal = $product->selling_price * $itemData['quantity'];
                $subtotal += $itemSubtotal;

                $itemsToProcess[] = [
                    'product' => $product,
                    'quantity' => $itemData['quantity'],
                    'selling_price' => $product->selling_price,
                    'subtotal' => $itemSubtotal
                ];
            }

            $discount = $request->discount ?? 0;
            $tax = ($subtotal - $discount) * 0.11; // PPN 11% sesuai dokumen lengkap
            $totalAmount = ($subtotal - $discount) + $tax;
            $changeAmount = $request->paid_amount - $totalAmount;

            // Proteksi jika uang yang dibayarkan kurang dari total belanja
            if ($request->paid_amount < $totalAmount) {
                return redirect()->back()->withErrors([
                    'paid_amount' => "Uang pembayaran kurang! Total tagihan: Rp " . number_format($totalAmount, 0, ',', '.')
                ])->withInput();
            }

            // 2. GENERATE INVOICE NUMBER
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Sale::count() + 1, 4, '0', STR_PAD_LEFT);

            // 3. SIMPAN INDUK TRANSAKSI PENJUALAN
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_name' => $request->customer_name ?? 'General Customer',
                'customer_phone' => $request->customer_phone,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $changeAmount,
                'user_id' => Auth::id()
            ]);

            // 4. SIMPAN DETAIL ITEMS, KURANGI STOK, & CATAT LOG
            foreach ($itemsToProcess as $proc) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $proc['product']->id,
                    'quantity' => $proc['quantity'],
                    'selling_price' => $proc['selling_price'],
                    'subtotal' => $proc['subtotal']
                ]);

                // Potong stok produk master
                $proc['product']->decrement('stock', $proc['quantity']);

                // Masuk ke Log Audit Trail Advanced Stock yang kita buat kemarin
                StockLog::create([
                    'product_id' => $proc['product']->id,
                    'quantity' => -$proc['quantity'], // Nilai minus karena barang keluar
                    'type' => 'reduce',
                    'reason' => "Penjualan Kasir (Nota: {$invoiceNumber})",
                    'user_id' => Auth::id()
                ]);
            }

            return redirect()->route('sales.index')->with('success', "Transaksi {$invoiceNumber} berhasil diselesaikan!");
        });
    }
}