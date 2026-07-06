<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">📦 Riwayat Purchase Order (PO)</h2>
            <a href="{{ route('purchases.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-xl shadow-md">+ Buat PO Baru</a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-[calc(100vh-65px)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl text-sm font-medium">✨ {{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="py-4 px-6">No. PO</th>
                            <th class="py-4 px-6">Distributor</th>
                            <th class="py-4 px-6">Tanggal</th>
                            <th class="py-4 px-6 text-right">Total Biaya</th>
                            <th class="py-4 px-6 text-center">Status Alur</th>
                            <th class="py-4 px-6 text-center">Aksi Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($purchases as $p)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-4 px-6 font-mono font-bold text-slate-900">{{ $p->po_number }}</td>
                                <td class="py-4 px-6 font-medium">{{ $p->distributor->name }}</td>
                                <td class="py-4 px-6 text-slate-500">{{ $p->purchase_date }}</td>
                                <td class="py-4 px-6 text-right font-bold text-slate-900">Rp {{ number_format($p->total_amount, 0, ',', '.') }}</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        {{ $p->status == 'Draft' ? 'bg-slate-100 text-slate-600' : '' }}
                                        {{ $p->status == 'Confirmed' ? 'bg-blue-50 text-blue-600' : '' }}
                                        {{ $p->status == 'Received' ? 'bg-amber-50 text-amber-600' : '' }}
                                        {{ $p->status == 'Completed' ? 'bg-emerald-50 text-emerald-600' : '' }}
                                    ">{{ $p->status }}</span>
                                </td>
                                <td class="py-4 px-6 text-center flex justify-center gap-2">
                                    @if($p->status == 'Draft')
                                        <form action="{{ route('purchases.updateStatus', [$p->id, 'Confirmed']) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-1 px-3 rounded-lg">✔️ Setujui (Confirm)</button>
                                        </form>
                                    @elseif($p->status == 'Confirmed')
                                        <form action="{{ route('purchases.updateStatus', [$p->id, 'Received']) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold py-1 px-3 rounded-lg">🚚 Terima Barang (Update Stok)</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">Transaksi Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-8 text-center text-slate-400">Belum ada transaksi pembelian tercatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>