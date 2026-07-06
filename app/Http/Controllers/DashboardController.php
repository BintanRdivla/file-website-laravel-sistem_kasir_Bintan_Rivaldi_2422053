<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung total pendapatan dari Penjualan Kasir
        $totalSales = Sale::sum('total_amount');
        $salesCount = Sale::count();

        // 2. Hitung total pengeluaran modal dari Purchase Order (status Received/Completed)
        $totalPurchases = Purchase::whereIn('status', ['Received', 'Completed'])->sum('total_amount');

        // 3. Kalkulasi Analisis Laba & Rugi (Pendapatan - Pengeluaran Modal)
        $profitOrLoss = $totalSales - $totalPurchases;

        // 4. Hitung total aset barang unik di gudang saat ini
        $totalProducts = Product::count();

        // Ambil 5 riwayat penjualan terakhir untuk pelaporan ringkas di dashboard
        $recentSales = Sale::with('user')->latest()->take(5)->get();

        return view('dashboard', compact('totalSales', 'salesCount', 'totalPurchases', 'profitOrLoss', 'totalProducts', 'recentSales'));
    }
}