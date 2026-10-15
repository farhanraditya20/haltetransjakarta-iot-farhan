<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Halte;

class HalteController extends Controller
{
    // Menampilkan halaman untuk penumpang
    public function index()
    {
        $haltes = Halte::all();
        return view('penumpang', compact('haltes'));
    }

    // Menampilkan halaman dasbor admin simulator
    public function admin()
    {
        $haltes = Halte::all();
        return view('admin', compact('haltes'));
    }

    // Memproses input update kepadatan dari admin
    public function update(Request $request, $id)
    {
        $halte = Halte::findOrFail($id);

        $halte->update([
            'jumlah_sekarang' => $request->jumlah_sekarang
        ]);

        return redirect()->back()->with('success', 'Data kepadatan berhasil diperbarui!');
    }
}