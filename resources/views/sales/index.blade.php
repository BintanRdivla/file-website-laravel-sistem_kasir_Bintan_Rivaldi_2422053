<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">🧾️ Riwayat Transaksi Penjualan (POS)</h2>
            <a href="{{ route('sales.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold py-2 px-4 rounded-xl shadow-md">💻 Buka Aplikasi Kasir</a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-[calc(100vh-65px)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ========================================================= --}}
            {{-- KODE INTEGRASI OTOMATIS (Sudah Berada di Posisi yang Benar) --}}
            {{-- ========================================================= --}}
@if(session('print_url'))
    <script>
        // Menggunakan window.onload agar browser menyelesaikan load halaman index terlebih dahulu
        window.onload = function() {
            setTimeout(function() {
                var printWindow = window.open("{{ session('print_url') }}", "_blank");
                
                // Cek jika diblokir oleh browser, berikan peringatan ke kasir
                if (!printWindow || printWindow.closed || typeof printWindow.closed == 'undefined') {
                    alert('Gagal membuka struk otomatis! Mohon izinkan Pop-up pada browser Anda, lalu klik tombol "Cetak Struk" manual pada tabel.');
                }
            }, 500); // Ditunda 0.5 detik agar browser siap
        };
    </script>
@endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="py-4 px-6">No. Invoice</th>
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">Metode</th>
                            <th class="py-4 px-6 text-right">Total Belanja</th>
                            <th class="py-4 px-6 text-right">Uang Bayar</th>
                            <th class="py-4 px-6 text-right">Kembalian</th>
                            <th class="py-4 px-6">Kasir</th>
                            <th class="py-4 px-6 text-center">Aksi</th> {{-- Tambah Kolom Aksi --}}
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($sales as $s)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-4 px-6 font-mono font-bold text-blue-600">{{ $s->invoice_number }}</td>
                                <td class="py-4 px-6 font-medium">{{ $s->customer_name }}</td>
                                <td class="py-4 px-6"><span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-bold text-xs">{{ $s->payment_method }}</span></td>
                                <td class="py-4 px-6 text-right font-bold text-slate-900">Rp {{ number_format($s->total_amount, 0, ',', '.') }}</td>
                                <td class="py-4 px-6 text-right text-emerald-600 font-medium">Rp {{ number_format($s->paid_amount, 0, ',', '.') }}</td>
                                <td class="py-4 px-6 text-right text-amber-600 font-medium">Rp {{ number_format($s->change_amount, 0, ',', '.') }}</td>
                                <td class="py-4 px-6 font-medium text-slate-500">👤 {{ $s->user->name }}</td>
                                
                                {{-- ========================================================= --}}
                                {{-- TOMBOL CETAK ULANG MANUVAL DI SETIAP BARIS TRANSAKSI --}}
                                {{-- ========================================================= --}}
                                <td class="py-4 px-6 text-center">
                                    <a href="{{ route('sales.print', $s->id) }}" target="_blank" class="inline-flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 font-semibold py-1 px-3 rounded-lg text-xs transition duration-150 shadow-sm border border-blue-200">
                                        🖨️ Cetak Struk
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-8 text-center text-slate-400">Belum ada transaksi kasir harian yang tercatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $sales->links() }}</div>
        </div>
    </div>
</x-app-layout>