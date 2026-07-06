<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Distributor;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // Tampilkan Daftar PO
    public function index() {
        $purchases = Purchase::with('distributor')->latest()->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    // Form Pembuatan PO Baru (Multiple Items)
    public function create() {
        $distributors = Distributor::all();
        $products = Product::all();
        return view('purchases.create', compact('distributors', 'products'));
    }

    // Simpan Transaksi PO (Status: Draft)
    public function store(Request $request) {
        $request->validate([
            'distributor_id' => 'required|exists:distributors,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            // Generate PO Number Otomatis
            $poNumber = 'PO-' . date('Ymd') . '-' . str_pad(Purchase::count() + 1, 4, '0', STR_PAD_LEFT);

            // Hitung kalkulasi angka otomatis
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
            $discount = $request->discount ?? 0;
            $tax = ($subtotal - $discount) * 0.11; // Contoh PPN 11%
            $totalAmount = ($subtotal - $discount) + $tax;

            // Simpan Induk PO
            $purchase = Purchase::create([
                'po_number' => $poNumber,
                'distributor_id' => $request->distributor_id,
                'purchase_date' => $request->purchase_date,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total_amount' => $totalAmount,
                'status' => 'Draft', // Default awal
                'user_id' => Auth::id()
            ]);

            // Simpan Rincian Items
            foreach ($request->items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price']
                ]);
            }
        });

        return redirect()->route('purchases.index')->with('success', 'Nota PO baru berhasil dibuat dengan status Draft!');
    }

    // WORKFLOW: Mengubah Status PO & Otomatisasi Update Stok Gudang
    public function updateStatus($id, $status) {
        $purchase = Purchase::with('items.product')->findOrFail($id);

        // Validasi urutan status agar aman
        if ($purchase->status == 'Received' || $purchase->status == 'Completed') {
            return redirect()->back()->with('error', 'Status transaksi ini sudah selesai dan tidak bisa diubah lagi.');
        }

        DB::transaction(function () use ($purchase, $status) {
            $purchase->status = $status;

            // Jika status diubah menjadi "Received" (Barang Diterima), UPDATE STOK OTOMATIS!
            if ($status == 'Received') {
                foreach ($purchase->items as $item) {
                    // Tambah stok ke master tabel produk
                    $item->product->increment('stock', $item->quantity);

                    // Catat ke Log Audit Trail Inventori yang kita buat kemarin
                    StockLog::create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'type' => 'add',
                        'reason' => "Penerimaan Barang Masuk (Nota: {$purchase->po_number})",
                        'user_id' => Auth::id()
                    ]);
                }
            }
            $purchase->save();
        });

        return redirect()->route('purchases.index')->with('success', "Status PO {$purchase->po_number} berhasil diperbarui menjadi {$status}!");
    }
}