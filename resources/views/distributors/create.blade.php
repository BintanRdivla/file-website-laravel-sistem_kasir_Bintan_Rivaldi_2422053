<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            ➕ Daftarkan Distributor Baru
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-[calc(100vh-65px)]">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-100 mx-4 sm:mx-0">
                <form action="{{ route('distributors.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nama Distributor / Perusahaan</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: PT. Sinar Abadi Sentosa" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">No. Telepon / WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 0812345678" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('phone') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Batas Kredit Utang (Credit Limit)</label>
                            <input type="number" name="credit_limit" value="{{ old('credit_limit', 0) }}" placeholder="0" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('credit_limit') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Alamat Kantor/Gudang Utama</label>
                        <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap distributor..." class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('address') }}</textarea>
                        @error('address') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-6">
                        <a href="{{ route('distributors.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors">Batal</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-5 rounded-xl shadow-md transition-all">
                            Simpan Distributor
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>