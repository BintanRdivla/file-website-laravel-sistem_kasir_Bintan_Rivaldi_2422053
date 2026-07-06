<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    // 1. Tampilkan Semua Distributor (Kecuali yang sudah di-soft delete) [cite: 112, 115]
    public function index()
    {
        $distributors = Distributor::all();
        return view('distributors.index', compact('distributors'));
    }

    // 2. Halaman Form Tambah Distributor 
    public function create()
    {
        return view('distributors.create');
    }

    // 3. Simpan Distributor Baru ke Database dengan Validasi [cite: 112, 113]
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'credit_limit' => 'required|numeric|min:0', // Validasi credit limit tracking 
        ]);

        Distributor::create($request->all());

        return redirect()->route('distributors.index')->with('success', 'Distributor baru berhasil ditambahkan!');
    }

    // 4. Aksi Soft Delete (Hapus Aman) 
    public function destroy($id)
    {
        $distributor = Distributor::findOrFail($id);
        $distributor->delete(); // Ini otomatis melakukan Soft Delete karena trait di Model 

        return redirect()->route('distributors.index')->with('success', 'Distributor berhasil dihapus (Soft Delete)!');
    }
}