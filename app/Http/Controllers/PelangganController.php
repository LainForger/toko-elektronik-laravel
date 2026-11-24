<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::latest()->get();
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp'          => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
        ]);

        $pelanggan = Pelanggan::create($request->only(['nama_pelanggan', 'no_hp', 'alamat']));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'pelanggan' => $pelanggan]);
        }

        return redirect()->back()->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Pelanggan berhasil dihapus.');
    }

    // JSON API endpoints
    public function apiIndex()
    {
        $pelanggans = Pelanggan::latest()->get();
        return response()->json($pelanggans);
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp'          => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
        ]);

        $pelanggan = Pelanggan::create($request->only(['nama_pelanggan', 'no_hp', 'alamat']));
        return response()->json(['success' => true, 'pelanggan' => $pelanggan]);
    }
}
