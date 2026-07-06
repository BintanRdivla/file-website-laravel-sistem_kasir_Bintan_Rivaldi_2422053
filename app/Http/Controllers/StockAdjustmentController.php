<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentController extends Controller
{
    // Tampilkan formulir penyesuaian dan riwayat log
    public function index()
    {
        $products = Product::all();
        $logs = StockLog::with(['product', 'user'])->latest()->paginate(10);
        return view('stock.adjustment', compact('products', 'logs'));
    }

    // Proses perubahan stok manual
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:add,reduce,damaged', // Sesuai dokumen 
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty = $request->quantity;

        // Tentukan apakah stok bertambah atau berkurang
        if ($request->type == 'add') {
            $product->increment('stock', $qty);
        } else {
            // Untuk 'reduce' atau 'damaged', pastikan stok tidak minus (Validasi Stok Otomatis) [cite: 228]
            if ($product->stock < $qty) {
                return redirect()->back()->withErrors(['quantity' => 'Stok saat ini tidak mencukupi untuk dikurangi!'])->withInput();
            }
            $product->decrement('stock', $qty);
        }

        // Simpan ke Log untuk keperluan Audit Trail & Report [cite: 138, 236]
        StockLog::create([
            'product_id' => $request->product_id,
            'quantity' => $request->type == 'add' ? $qty : -$qty,
            'type' => $request->type,
            'reason' => $request->reason,
            'user_id' => Auth::id(), // ID Admin/User yang sedang login 
        ]);

        return redirect()->route('stock.adjustment')->with('success', 'Stok produk berhasil diperbarui secara manual!');
    }
}