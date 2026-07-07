<x-app-layout>
    {{-- HIDE NAVBAR BAWAAN BREEZE KHUSUS KASIR AGAR TIDAK BISA KE DASHBOARD --}}
    @if(Auth::user()->role === 'Kasir')
        <style>
            nav { display: none !important; }
            body { padding-top: 0 !important; }
        </style>
    @endif

    <div class="bg-slate-900 min-h-screen text-slate-100 font-sans antialiased">
        <div class="grid grid-cols-12 gap-0 h-screen overflow-hidden">
            
            <div class="col-span-12 lg:col-span-8 flex flex-col bg-slate-800 border-r border-slate-700 h-full">
                
                <div class="p-4 bg-slate-850 border-b border-slate-700 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">🔍</span>
                            <input type="text" id="search-product" placeholder="Cari Produk berdasarkan Nama atau Barcode... (F1)" class="w-full pl-9 pr-4 py-2 bg-slate-700 border border-slate-600 rounded-xl text-xs text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        <div class="text-[11px] font-mono bg-slate-900 px-3 py-2 rounded-xl border border-slate-700 text-blue-400 flex items-center gap-2">
                            <span>🟢 Operator: <span class="font-bold text-white">{{ Auth::user()->name }}</span></span>
                            {{-- Tambah tombol logout cepat jika navigasi atas disembunyikan --}}
                            @if(Auth::user()->role === 'Kasir')
                                <form method="POST" action="{{ route('logout') }}" class="inline ml-2 pl-2 border-l border-slate-700">
                                    @csrf
                                    <button type="submit" class="text-rose-400 hover:text-rose-300 font-sans font-bold">🚪 Keluar</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    {{-- PERBAIKAN: SATU TEMPAT KATEGORI TANPA DUPLIKAT --}}
                    @php
                        $categories = $products->map(function($product) {
                            return is_object($product->category) ? $product->category->name : $product->category;
                        })->unique()->filter();
                    @endphp

                    <div class="flex items-center gap-1.5 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-slate-900 snap-x whitespace-nowrap">
                        <button type="button" class="category-btn active snap-center px-4 py-1.5 bg-blue-600 text-white rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all" data-category="all">
                            📦 Semua Barang
                        </button>
                        
                        @foreach($categories as $catName)
                            <button type="button" class="category-btn snap-center px-4 py-1.5 bg-slate-700 text-slate-300 hover:bg-slate-600 rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all" data-category="{{ Str::slug($catName) }}">
                                {{ $catName }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="p-4 flex-1 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 bg-slate-900" id="product-grid">
                    @foreach($products as $p)
                        <div class="product-card group bg-slate-800 border border-slate-700/60 rounded-xl p-3 flex flex-col justify-between shadow-sm hover:border-blue-500 hover:bg-slate-750 cursor-pointer transition-all active:scale-[0.98]" 
                             data-id="{{ $p->id }}" 
                             data-name="{{ $p->name }}" 
                             data-price="{{ $p->selling_price }}" 
                             data-stock="{{ $p->stock }}" 
                             data-code="{{ $p->code }}"
                             data-category-slug="{{ Str::slug(is_object($p->category) ? $p->category->name : ($p->category ?? 'unassigned')) }}">
                            
                            <div>
                                <div class="flex justify-between items-start gap-1">
                                    <span class="text-[9px] font-mono bg-slate-750 text-slate-400 px-1.5 py-0.5 rounded border border-slate-700">
                                        {{ $p->code }}
                                    </span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $p->stock > 5 ? 'bg-emerald-950 text-emerald-400' : 'bg-rose-950 text-rose-400' }}">
                                        Stok: {{ $p->stock }}
                                    </span>
                                </div>

                                <div class="my-2.5 w-full h-24 bg-slate-900 rounded-lg overflow-hidden border border-slate-700 flex items-center justify-center">
                                    @if($p->hasMedia('product_images'))
                                        <img src="{{ $p->getFirstMediaUrl('product_images') }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <img src="https://placehold.co/150?text=No+Image" alt="No Image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @endif
                                </div>

                                <h4 class="font-bold text-xs text-slate-200 line-clamp-1 group-hover:text-white transition-colors">
                                    {{ $p->name }}
                                </h4>
                            </div>

                            <div class="mt-2 pt-2 border-t border-slate-700/50 flex items-center justify-between">
                                <span class="text-xs font-black text-emerald-400 font-mono">
                                    Rp {{ number_format($p->selling_price, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] text-slate-500 group-hover:text-blue-400 transition-colors">➕ Ambil</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 flex flex-col bg-slate-850 h-full overflow-hidden">
                <form action="{{ route('sales.store') }}" method="POST" class="flex flex-col h-full" id="pos-form">
                    @csrf
                    
                    <div class="p-4 border-b border-slate-700 bg-slate-800">
                        <h3 class="text-xs font-black text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                            🛒 Keranjang Nota Penjualan
                        </h3>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-2.5 font-mono text-xs" id="cart-container">
                        <div id="cart-empty-state" class="text-center py-12 text-slate-500 font-sans">
                            <span class="text-3xl block mb-2">📥</span>
                            Keranjang kosong.<br>Klik produk di sebelah kiri untuk memilih.
                        </div>
                    </div>

                    <div class="bg-slate-800 border-t border-slate-700 p-4 space-y-3">
                        
                        <div class="space-y-1.5 text-[11px] text-slate-400 border-b border-slate-700 pb-2.5">
                            <div class="flex justify-between">
                                <span>Subtotal Item:</span>
                                <span id="subtotal-display" class="font-bold text-white">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>PPN (11%):</span>
                                <span id="tax-display" class="font-bold text-white">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Potongan Diskon:</span>
                                <input type="number" id="discount-input" name="discount" value="0" min="0" class="w-20 bg-slate-700 border border-slate-600 rounded px-1.5 py-0.5 text-center text-white text-[10px] font-bold focus:outline-none focus:border-blue-500">
                            </div>
                        </div>

                        <div class="bg-emerald-600 text-white p-3 rounded-xl flex justify-between items-center shadow-inner">
                            <span class="text-[10px] font-bold uppercase tracking-wider">Total Tagihan</span>
                            <span class="text-xl font-black font-mono" id="grand-total-display">Rp 0</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <label class="block text-[10px] text-slate-400 mb-1">Metode</label>
                                <select name="payment_method" class="w-full bg-slate-700 border border-slate-600 rounded-xl p-2 text-white font-bold focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                    <option value="Cash">💵 Cash</option>
                                    <option value="Debit">💳 Debit</option>
                                    <option value="Credit">💳 Credit</option>
                                    <option value="Transfer Bank">🏦 Transfer</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 mb-1">Uang Diterima (Rp)</label>
                                <input type="number" id="paid-input" name="paid_amount" value="0" min="0" class="w-full bg-slate-700 border border-slate-600 rounded-xl p-2 text-white font-black font-mono focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-xs bg-slate-900 p-2.5 rounded-xl border border-slate-700">
                            <span class="text-slate-400">Uang Kembalian:</span>
                            <span id="change-display" class="font-black text-amber-400 text-sm">Rp 0</span>
                        </div>

                        <input type="hidden" name="customer_name" value="General Customer">
                        <input type="hidden" name="customer_phone" value="-">

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <button type="button" id="btn-cancel" class="bg-orange-600 hover:bg-orange-700 text-white text-[11px] font-bold py-2.5 rounded-xl transition-all uppercase tracking-wider">
                                Batal (ESC)
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-black py-2.5 rounded-xl shadow-md shadow-blue-900/30 transition-all uppercase tracking-wider">
                                Bayar (F2)
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        let cart = {};

        // 1. EVENT CLICK KARTU PRODUK
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const price = parseFloat(this.getAttribute('data-price'));
                const stock = parseInt(this.getAttribute('data-stock'));
                const code = this.getAttribute('data-code');

                if (stock <= 0) {
                    alert('🚨 Maaf, stok item produk ini habis di master gudang!');
                    return;
                }

                if (cart[id]) {
                    if (cart[id].quantity >= stock) {
                        alert('⚠️ Batas kuantitas! Jumlah belanja tidak boleh melebihi sisa stok gudang.');
                        return;
                    }
                    cart[id].quantity++;
                } else {
                    cart[id] = { name, price, code, quantity: 1, stock };
                }

                renderCart();
            });
        });

        // 2. RENDER LIST KERANJANG
        function renderCart() {
            const container = document.getElementById('cart-container');
            const emptyState = document.getElementById('cart-empty-state');
            
            container.innerHTML = '';
            const keys = Object.keys(cart);
            
            if (keys.length === 0) {
                container.appendChild(emptyState);
                document.getElementById('subtotal-display').innerText = 'Rp 0';
                document.getElementById('tax-display').innerText = 'Rp 0';
                document.getElementById('grand-total-display').innerText = 'Rp 0';
                document.getElementById('change-display').innerText = 'Rp 0';
                return;
            }

            let subtotal = 0;
            let index = 0;

            keys.forEach(id => {
                const item = cart[id];
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;

                const row = document.createElement('div');
                row.className = "bg-slate-800 border border-slate-700 p-2.5 rounded-xl flex flex-col gap-1.5 relative";
                row.innerHTML = `
                    <div class="flex justify-between pr-4">
                        <span class="font-bold text-slate-200 truncate max-w-[180px]">${item.name}</span>
                        <span class="text-emerald-400 font-bold">Rp ${(itemTotal).toLocaleString('id-ID')}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                        <span>${item.code} @ Rp ${item.price.toLocaleString('id-ID')}</span>
                        <div class="flex items-center gap-2 bg-slate-900 border border-slate-700 rounded-lg p-0.5">
                            <button type="button" class="px-2 py-0.5 text-slate-400 hover:text-white" onclick="updateQty('${id}', -1)">-</button>
                            <span class="text-white font-bold px-1">${item.quantity}</span>
                            <button type="button" class="px-2 py-0.5 text-slate-400 hover:text-white" onclick="updateQty('${id}', 1)">+</button>
                        </div>
                    </div>
                    <input type="hidden" name="items[${index}][product_id]" value="${id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                    <button type="button" class="absolute top-2 right-2 text-slate-500 hover:text-rose-400 text-[10px]" onclick="deleteItem('${id}')">✕</button>
                `;
                container.appendChild(row);
                index++;
            });

            const discount = parseFloat(document.getElementById('discount-input').value) || 0;
            const taxableAmount = Math.max(0, subtotal - discount);
            const tax = taxableAmount * 0.11;
            const grandTotal = taxableAmount + tax;

            document.getElementById('subtotal-display').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('tax-display').innerText = 'Rp ' + tax.toLocaleString('id-ID');
            document.getElementById('grand-total-display').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
            
            calculateChange(grandTotal);
        }

        window.updateQty = function(id, delta) {
            if (!cart[id]) return;
            const newQty = cart[id].quantity + delta;
            if (newQty <= 0) { deleteItem(id); return; }
            if (newQty > cart[id].stock) { alert('⚠️ Stok tidak mencukupi!'); return; }
            cart[id].quantity = newQty;
            renderCart();
        };

        window.deleteItem = function(id) {
            delete cart[id];
            renderCart();
        };

        function calculateChange(forcedGrandTotal = null) {
            let grandTotal = forcedGrandTotal;
            if (grandTotal === null) {
                const text = document.getElementById('grand-total-display').innerText;
                grandTotal = parseFloat(text.replace(/[^0-9]/g, '')) || 0;
            }
            const paid = parseFloat(document.getElementById('paid-input').value) || 0;
            const change = paid - grandTotal;
            document.getElementById('change-display').innerText = 'Rp ' + Math.max(0, change).toLocaleString('id-ID');
        }

        document.getElementById('paid-input').addEventListener('input', () => calculateChange(null));
        document.getElementById('discount-input').addEventListener('input', renderCart);

        // 6. FILTER TAB KATEGORI
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.category-btn').forEach(b => {
                    b.classList.remove('active', 'bg-blue-600', 'text-white');
                    b.classList.add('bg-slate-700', 'text-slate-300');
                });
                
                this.classList.remove('bg-slate-700', 'text-slate-300');
                this.classList.add('active', 'bg-blue-600', 'text-white');

                const targetSlug = this.getAttribute('data-category');
                document.querySelectorAll('.product-card').forEach(card => {
                    if (targetSlug === 'all' || card.getAttribute('data-category-slug') === targetSlug) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // 7. REAL-TIME SEARCH BOX
        document.getElementById('search-product').addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.getAttribute('data-name').toLowerCase();
                const code = card.getAttribute('data-code').toLowerCase();
                if (name.includes(query) || code.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // 8. GLOBAL HOTKEYS SHORTCUT KASIR KILAT
        window.addEventListener('keydown', function(e) {
            if (e.key === 'F1') { e.preventDefault(); document.getElementById('search-product').focus(); }
            if (e.key === 'F2') { e.preventDefault(); document.getElementById('pos-form').submit(); }
            if (e.key === 'Escape') {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin mengosongkan seluruh isi keranjang nota saat ini?')) {
                    cart = {}; renderCart();
                }
            }
        });
        // 9. OTOMATIS POP-UP CETAK STRUK BILA SESSION TERSEDIA
        @if(session('print_url'))
            // Membuka jendela cetak struk di tab baru secara otomatis
            const printWindow = window.open("{{ session('print_url') }}", '_blank');
            
            // Fokuskan ke jendela cetak jika diblokir oleh pop-up blocker browser
            if (printWindow) {
                printWindow.focus();
            } else {
                alert('🚨 Gagal membuka cetak struk otomatis! Mohon izinkan izin "Pop-up" pada browser Anda.');
            }
        @endif

        // Notifikasi Sukses Tambahan (Opsional)
        @if(session('success'))
            alert("{{ session('success') }}");
        @endif
    </script>
</x-app-layout>