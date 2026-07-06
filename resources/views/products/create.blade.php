<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            ➕ Tambah Produk Baru
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-[calc(100vh-65px)]">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-100 mx-4 sm:mx-0">
                <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Kode / Barcode</label>
                            <input type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: BRG-001" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('code') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Kategori</label>
                            <select name="category_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama barang lengkap" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Harga Beli (Modal)</label>
                            <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" placeholder="0" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('purchase_price') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Harga Jual</label>
                            <input type="number" name="selling_price" value="{{ old('selling_price') }}" placeholder="0" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('selling_price') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Stok Awal</label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('stock') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Batas Minimum Stok (Alert)</label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('min_stock') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Batas Maksimum Stok</label>
                            <input type="number" name="max_stock" value="{{ old('max_stock', 100) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Link Gambar Produk (URL Internet)</label>
                        <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="Contoh: https://images.unsplash.com/photo-xxx..." class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" oninput="previewImage()">
                        @error('image_url') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-col items-center justify-center">
                        <span class="text-[10px] text-slate-400 font-bold uppercase mb-2">Live Preview Gambar</span>
                        <div class="w-32 h-32 rounded-xl border border-slate-200 bg-white flex items-center justify-center overflow-hidden">
                            <img id="image-preview" src="https://placehold.co/150?text=No+Image" alt="Preview" class="w-full h-full object-cover transition-all">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-6">
                        <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors">Batal</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-5 rounded-xl shadow-md transition-all">
                            Simpan Produk
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <script>
        function previewImage() {
            const urlInput = document.getElementById('image_url').value;
            const preview = document.getElementById('image-preview');
            if(urlInput) {
                preview.src = urlInput;
            } else {
                preview.src = 'https://placehold.co/150?text=No+Image';
            }
        }
    </script>
</x-app-layout>