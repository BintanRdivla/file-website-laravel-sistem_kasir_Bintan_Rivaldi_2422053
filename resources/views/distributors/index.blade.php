<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                🚚 Manajemen Distributor / Supplier
            </h2>
            <a href="{{ route('distributors.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-xl shadow-md transition-all">
                + Tambah Distributor
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
                                <th class="py-4 px-6">Nama Perusahaan</th>
                                <th class="py-4 px-6">No. Telepon</th>
                                <th class="py-4 px-6">Alamat</th>
                                <th class="py-4 px-6 text-right">Batas Kredit (Credit Limit)</th>
                                <th class="py-4 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            @forelse($distributors as $distributor)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-6 font-bold text-slate-900">{{ $distributor->name }}</td>
                                    <td class="py-4 px-6 font-mono text-xs">{{ $distributor->phone }}</td>
                                    <td class="py-4 px-6 text-slate-500 max-w-xs truncate">{{ $distributor->address }}</td>
                                    <td class="py-4 px-6 text-right font-semibold text-amber-600">
                                        Rp {{ number_format($distributor->credit_limit, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <form action="{{ route('distributors.destroy', $distributor->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus distributor ini secara aman?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400">
                                        <span class="text-3xl block mb-2">🚚</span>
                                        Belum ada data distributor resmi tercatat.
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