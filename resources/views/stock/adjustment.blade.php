<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            ⚙️ Advanced Stock Management (Adjustment)
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-[calc(100vh-65px)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-fit mx-4 sm:mx-0">
                <h3 class="font-bold text-slate-800 mb-4 text-base">Form Penyesuaian Stok</h3>
                
                <form action="{{ route('stock.adjustment.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Pilih Produk</label>
                        <select name="product_id" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">[{{ $product->code }}] {{ $product->name }} (Stok: {{ $product->stock }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Jenis Penyesuaian</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="border border-slate-200 rounded-xl p-3 text-center cursor-pointer hover:bg-slate-50 block">
                                <input type="radio" name="type" value="add" checked class="text-blue-600 focus:ring-blue-500 mb-1 block mx-auto">
                                <span class="text-xs font-bold text-slate-700">Tambah</span>
                            </label>
                            <label class="border border-slate-200 rounded-xl p-3 text-center cursor-pointer hover:bg-slate-50 block">
                                <input type="radio" name="type" value="reduce" class="text-amber-600 focus:ring-amber-500 mb-1 block mx-auto">
                                <span class="text-xs font-bold text-slate-700">Kurangi</span>
                            </label>
                            <label class="border border-slate-200 rounded-xl p-3 text-center cursor-pointer hover:bg-slate-50 block">
                                <input type="radio" name="type" value="damaged" class="text-rose-600 focus:ring-rose-500 mb-1 block mx-auto">
                                <span class="text-xs font-bold text-slate-700">Rusak</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Jumlah (Quantity)</label>
                        <input type="number" name="quantity" min="1" placeholder="0" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500" required>
                        @error('quantity') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Alasan / Keterangan Karyawan</label>
                        <textarea name="reason" rows="2" placeholder="Contoh: Barang pecah di gudang / Stok Opname berkala" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500" required>{{ old('reason') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold py-3 rounded-xl shadow-md transition-all">
                        Eksekusi Penyesuaian Stok
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mx-4 sm:mx-0">
                <h3 class="font-bold text-slate-800 mb-4 text-base">📊 Log Aktivitas & Jejak Audit Inventori</h3>
                
                @if(session('success'))
                    <div class="mb-4 p-3 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl text-xs font-medium">
                        ✨ {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4">Produk</th>
                                <th class="py-3 px-4 text-center">Jenis</th>
                                <th class="py-3 px-4 text-right">Jumlah</th>
                                <th class="py-3 px-4">Alasan</th>
                                <th class="py-3 px-4">Oleh (User)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($logs as $log)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3 px-4 text-slate-400 font-mono">{{ $log->created_at->format('d/m H:i') }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-900">{{ $log->product->name }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @if($log->type == 'add')
                                            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-600 font-bold">Masuk</span>
                                        @elseif($log->type == 'reduce')
                                            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-600 font-bold">Keluar</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-600 font-bold">🚨 Rusak</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono font-bold {{ $log->quantity > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $log->quantity > 0 ? '+'.$log->quantity : $log->quantity }}
                                    </td>
                                    <td class="py-3 px-4 text-slate-500">{{ $log->reason }}</td>
                                    <td class="py-3 px-4 font-medium text-slate-600">👤 {{ $log->user->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">Belum ada riwayat aktivitas penyesuaian manual.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>