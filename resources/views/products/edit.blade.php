<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-slate-800 leading-tight">
            ✏️ Edit Produk: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                
                <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kode / Barcode</label>
                            <input type="text" name="code" value="{{ old('code', $product->code) }}" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                            @error('code') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Produk</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                            @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kategori</label>
                        <select name="category_id" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga Beli (Modal)</label>
                            <input type="number" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                            @error('purchase_price') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga Jual</label>
                            <input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                            @error('selling_price') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jumlah Stok</label>
    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500" required>
    @error('stock') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
</div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Batas Minimum Stok (Alert)</label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock ?? 5) }}" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                            @error('min_stock') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Batas Maksimum Stok</label>
                            <input type="number" name="max_stock" value="{{ old('max_stock', $product->max_stock ?? 100) }}" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                            @error('max_stock') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Link Gambar Produk (URL Internet)</label>
                        <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="Contoh: https://images.unsplash.com/photo-xxx..." class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500" oninput="previewImage()">
                        @error('image_url') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-col items-center justify-center">
                        <span class="text-[10px] text-slate-400 font-bold uppercase mb-2">Live Preview Gambar</span>
                        <div class="w-32 h-32 rounded-xl border border-slate-200 bg-white flex items-center justify-center overflow-hidden">
                            <img id="image-preview" src="{{ $product->image_url ?? 'https://placehold.co/150?text=No+Image' }}" alt="Preview" class="w-full h-full object-cover transition-all">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 mt-4">
                        <a href="{{ route('products.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl transition-all">Batal</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-md transition-all">Simpan Perubahan</button>
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