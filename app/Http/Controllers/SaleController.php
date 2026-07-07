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
        
        // Buat variabel penampung di luar scope transaction closure
        $createdSale = null;

        DB::transaction(function () use ($request, &$createdSale) {
            $subtotal = 0;
            $itemsToProcess = [];

            // 1. VALIDASI STOK REAL-TIME & HITUNG SUBTOTAL
            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                if ($product->stock < $itemData['quantity']) {
                    throw new \Exception("Stok untuk produk '{$product->name}' tidak mencukupi!");
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
            $tax = ($subtotal - $discount) * 0.11; 
            $totalAmount = ($subtotal - $discount) + $tax;
            $changeAmount = $request->paid_amount - $totalAmount;

            if ($request->paid_amount < $totalAmount) {
                throw new \Exception("Uang pembayaran kurang!");
            }

            // 2. GENERATE INVOICE NUMBER
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Sale::count() + 1, 4, '0', STR_PAD_LEFT);

            // 3. SIMPAN INDUK TRANSAKSI PENJUALAN
            $createdSale = Sale::create([
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
                    'sale_id' => $createdSale->id,
                    'product_id' => $proc['product']->id,
                    'quantity' => $proc['quantity'],
                    'selling_price' => $proc['selling_price'],
                    'subtotal' => $proc['subtotal']
                ]);

                $proc['product']->decrement('stock', $proc['quantity']);

                StockLog::create([
                    'product_id' => $proc['product']->id,
                    'quantity' => -$proc['quantity'],
                    'type' => 'reduce',
                    'reason' => "Penjualan Kasir (Nota: {$invoiceNumber})",
                    'user_id' => Auth::id()
                ]);
            }
        });

        // Keluarkan return redirect ke luar fungsi closure transaksi agar aman
// ... (Kode transaksi di bagian atas tetap utuh) ...

        // PERBAIKAN: Redirect kembali ke halaman kasir agar siap melayani transaksi berikutnya
        return redirect()->route('sales.create')
            ->with('success', "Transaksi {$createdSale->invoice_number} berhasil diselesaikan!")
            ->with('print_url', route('sales.print', $createdSale->id)); // 'sales.print' disesuaikan dengan nama route cetak Anda
    }

    // Tampilkan Halaman Print Struk
    public function printReceipt($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id); 
        return view('sales.receipt', compact('sale'));
    }
}