<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            📊 POS Kasir & Dashboard Analitik Utama
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-[calc(100vh-65px)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('error'))
                <div class="mx-4 sm:mx-0 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-xl font-bold text-sm shadow-sm transition-all animate-bounce">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mx-4 sm:mx-0">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Penjualan</p>
                        <h3 class="text-xl font-black text-slate-900 mt-1">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                        <p class="text-[10px] text-emerald-600 font-medium mt-0.5">💰 Dari {{ $salesCount }} transaksi retail</p>
                    </div>
                    <span class="text-3xl bg-emerald-50 p-3 rounded-xl">📈</span>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modal Kulakan (PO)</p>
                        <h3 class="text-xl font-black text-slate-900 mt-1">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</h3>
                        <p class="text-[10px] text-slate-500 mt-0.5">🚚 Stok masuk dari distributor</p>
                    </div>
                    <span class="text-3xl bg-amber-50 p-3 rounded-xl">📉</span>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Analisis Laba Bersih</p>
                        <h3 class="text-xl font-black {{ $profitOrLoss >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-1">
                            Rp {{ number_format($profitOrLoss, 0, ',', '.') }}
                        </h3>
                        <p class="text-[10px] text-slate-500 mt-0.5">✨ Selisih margin pendapatan</p>
                    </div>
                    <span class="text-3xl {{ $profitOrLoss >= 0 ? 'bg-emerald-50' : 'bg-rose-50' }} p-3 rounded-xl">✨</span>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Varian Produk</p>
                        <h3 class="text-xl font-black text-slate-900 mt-1">{{ $totalProducts }} Item</h3>
                        <p class="text-[10px] text-blue-600 font-medium mt-0.5">📦 Tercatat di master gudang</p>
                    </div>
                    <span class="text-3xl bg-blue-50 p-3 rounded-xl">📦</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mx-4 sm:mx-0">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-800">🚀 Menu Navigasi Kilat Sistem</h3>
                    <p class="text-xs text-slate-400">Hak akses menu disesuaikan otomatis dengan posisi akun Anda: <span class="bg-slate-100 px-2 py-0.5 rounded text-slate-700 font-bold font-mono">{{ Auth::user()->role }}</span></p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 pt-2">
                    
                    @if(in_array(Auth::user()->role, ['Admin', 'Kasir']))
                        <a href="{{ route('sales.create') }}" class="group flex flex-col items-center p-4 bg-slate-50 hover:bg-emerald-600 rounded-xl border border-slate-100 transition-all text-center">
                            <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">💻</span>
                            <span class="text-xs font-bold text-slate-700 group-hover:text-white">Mesin Kasir (POS)</span>
                        </a>
                    @endif

                    @if(in_array(Auth::user()->role, ['Admin', 'Manajer']))
                        <a href="{{ route('products.index') }}" class="group flex flex-col items-center p-4 bg-slate-50 hover:bg-blue-600 rounded-xl border border-slate-100 transition-all text-center">
                            <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">📦</span>
                            <span class="text-xs font-bold text-slate-700 group-hover:text-white">Master Produk</span>
                        </a>
                    @endif

                    @if(in_array(Auth::user()->role, ['Admin', 'Manajer']))
                        <a href="{{ route('distributors.index') }}" class="group flex flex-col items-center p-4 bg-slate-50 hover:bg-indigo-600 rounded-xl border border-slate-100 transition-all text-center">
                            <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">🚚</span>
                            <span class="text-xs font-bold text-slate-700 group-hover:text-white">Data Distributor</span>
                        </a>
                    @endif

                    @if(in_array(Auth::user()->role, ['Admin', 'Manajer']))
                        <a href="{{ route('stock.adjustment') }}" class="group flex flex-col items-center p-4 bg-slate-50 hover:bg-slate-800 rounded-xl border border-slate-100 transition-all text-center">
                            <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">⚙️</span>
                            <span class="text-xs font-bold text-slate-700 group-hover:text-white">Adjustment Stok</span>
                        </a>
                    @endif

                    @if(in_array(Auth::user()->role, ['Admin', 'Manajer']))
                        <a href="{{ route('purchases.index') }}" class="group flex flex-col items-center p-4 bg-slate-50 hover:bg-amber-500 rounded-xl border border-slate-100 transition-all text-center">
                            <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">📥</span>
                            <span class="text-xs font-bold text-slate-700 group-hover:text-white">Riwayat PO Belanja</span>
                        </a>
                    @endif

                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mx-4 sm:mx-0">
                <h3 class="text-base font-bold text-slate-800 mb-4">📋 5 Transaksi Kasir Terakhir (Live Report)</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 font-bold uppercase border-b border-slate-100">
                                <th class="p-3">No. Nota</th>
                                <th class="p-3">Nama Pelanggan</th>
                                <th class="p-3">Cara Bayar</th>
                                <th class="p-3 text-right">Total Nominal</th>
                                <th class="p-3">Petugas Kasir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($recentSales as $sale)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="p-3 font-mono font-bold text-blue-600">{{ $sale->invoice_number }}</td>
                                    <td class="p-3 font-medium">{{ $sale->customer_name }}</td>
                                    <td class="p-3"><span class="bg-slate-100 text-slate-700 font-bold px-2 py-0.5 rounded">{{ $sale->payment_method }}</span></td>
                                    <td class="p-3 text-right font-black">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                    <td class="p-3 text-slate-400">👤 {{ $sale->user->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-slate-400">Belum ada aktivitas penjualan kasir hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>