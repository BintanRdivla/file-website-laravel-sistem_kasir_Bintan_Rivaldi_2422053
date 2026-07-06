<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">➕ Buat Nota Pemakaian / Pembelian (PO) Baru</h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-[calc(100vh-65px)]">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <form action="{{ route('purchases.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Pilih Distributor</label>
                            <select name="distributor_id" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($distributors as $d) <option value="{{ $d->id }}">{{ $d->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Tanggal Pembelian</label>
                            <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500" required>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-slate-800 text-sm">🛒 Daftar Rincian Barang Belanja</h3>
                            <button type="button" id="add-item-btn" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-1.5 px-3 rounded-lg">+ Tambah Baris Barang</button>
                        </div>

                        <div id="items-container" class="space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 item-row items-end">
                                <div class="sm:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Pilih Produk</label>
                                    <select name="items[0][product_id]" class="w-full rounded-xl border-slate-200 text-xs focus:ring-blue-500" required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($products as $p) <option value="{{ $p->id }}">{{ $p->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Jumlah (Qty)</label>
                                    <input type="number" name="items[0][quantity]" min="1" placeholder="0" class="w-full rounded-xl border-slate-200 text-xs focus:ring-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Harga Modal (Satuan)</label>
                                    <input type="number" name="items[0][unit_price]" min="0" placeholder="Rp 0" class="w-full rounded-xl border-slate-200 text-xs focus:ring-blue-500" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full sm:w-1/3 ml-auto border-t border-slate-100 pt-4">
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Potongan Diskon Global (Rp)</label>
                        <input type="number" name="discount" value="0" min="0" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                        <a href="{{ route('purchases.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-500">Batal</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-6 rounded-xl shadow-md">Simpan Nota PO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let rowIndex = 1;
        document.getElementById('add-item-btn').addEventListener('click', function() {
            let container = document.getElementById('items-container');
            let newRow = document.createElement('div');
            newRow.className = "grid grid-cols-1 sm:grid-cols-4 gap-3 item-row items-end";
            newRow.innerHTML = `
                <div class="sm:col-span-2">
                    <select name="items[${rowIndex}][product_id]" class="w-full rounded-xl border-slate-200 text-xs focus:ring-blue-500" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($products as $p) <option value="{{ $p->id }}">{{ $p->name }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <input type="number" name="items[${rowIndex}][quantity]" min="1" placeholder="0" class="w-full rounded-xl border-slate-200 text-xs focus:ring-blue-500" required>
                </div>
                <div>
                    <input type="number" name="items[${rowIndex}][unit_price]" min="0" placeholder="Rp 0" class="w-full rounded-xl border-slate-200 text-xs focus:ring-blue-500" required>
                </div>
                <button type="button" class="text-rose-600 hover:text-rose-800 text-xs font-bold pb-3 text-left" onclick="this.parentElement.remove()">❌ Hapus</button>
            `;
            container.appendChild(newRow);
            rowIndex++;
        });
    </script>
</x-app-layout>