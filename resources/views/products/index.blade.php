<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                📦 Manajemen Produk / Barang
            </h2>
            <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-xl shadow-md transition-all">
                + Tambah Produk Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-[calc(100vh-65px)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 mx-4 sm:mx-0 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl shadow-sm text-sm font-medium">
                    ✨ {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mx-4 sm:mx-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                                <th class="py-4 px-6 w-16 text-center">Foto</th>
                                <th class="py-4 px-6">Kode</th>
                                <th class="py-4 px-6">Nama Produk</th>
                                <th class="py-4 px-6">Kategori</th>
                                <th class="py-4 px-6 text-right">Harga Beli</th>
                                <th class="py-4 px-6 text-right">Harga Jual</th>
                                <th class="py-4 px-6 text-right">Margin Untung</th>
                                <th class="py-4 px-6 text-center">Stok</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-right w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            @forelse($products as $product)
                                <tr class="hover:bg-slate-50/80 transition-colors">
<td class="py-4 px-6 whitespace-nowrap">
    <div class="w-12 h-12 rounded-lg border border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center mx-auto">
        @if($product->hasMedia('product_images'))
            <img src="{{ $product->getFirstMediaUrl('product_images') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
            {{-- Jika gagal atau kosong, fallback ke placeholder default --}}
            <img src="https://placehold.co/150?text=No+Image" alt="No Image" class="w-full h-full object-cover">
        @endif
    </div>
</td>

                                    <td class="py-4 px-6 font-mono text-xs font-bold text-slate-500">{{ $product->code }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-900">{{ $product->name }}</td>
                                    <td class="py-4 px-6 text-slate-500">{{ $product->category->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-right font-medium">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-right font-semibold text-slate-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-right font-medium text-emerald-600 bg-emerald-50/30">
                                        Rp {{ number_format($product->profit_margin ?? ($product->selling_price - $product->purchase_price), 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-center font-bold">{{ $product->stock }}</td>
                                    
                                    <td class="py-4 px-6 text-center">
                                        @if($product->stock <= ($product->min_stock ?? 5))
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 animate-pulse">
                                                ⚠️ Stok Menipis
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">
                                                ✓ Aman
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-6 text-right text-xs font-medium space-x-1 whitespace-nowrap">
                                        <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1.5 rounded-lg font-bold transition-all">
                                            Edit
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-900 bg-rose-50 px-3 py-1.5 rounded-lg font-bold transition-all">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-12 text-center text-slate-400">
                                        <span class="text-3xl block mb-2">📦</span>
                                        Belum ada data produk. Klik "+ Tambah Produk Baru" untuk mengisi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>