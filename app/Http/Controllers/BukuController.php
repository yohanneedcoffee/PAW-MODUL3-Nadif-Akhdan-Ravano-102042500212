<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar semua buku.
     */
    public function index()
    {
        $buku = Buku::latest()->paginate(10);

        return view('buku.index', compact('buku'));
    }

    /**
     * Menampilkan form tambah buku baru.
     */
    public function create()
    {
        return view('buku.create');
    }

    /**
     * Menyimpan buku baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|max:100',
            'penulis' => 'required|max:100',
            'penerbit' => 'required|max:100',
            'tahun_terbit' => 'required|integer',
            'stok' => 'required|integer|min:0',
        ], [
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.max' => 'Judul buku maksimal 100 karakter.',
            'penulis.required' => 'Penulis buku wajib diisi.',
            'penulis.max' => 'Penulis buku maksimal 100 karakter.',
            'penerbit.required' => 'Penerbit buku wajib diisi.',
            'penerbit.max' => 'Penerbit buku maksimal 100 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
            'stok.required' => 'Stok buku wajib diisi.',
            'stok.integer' => 'Stok buku harus berupa angka.',
            'stok.min' => 'Stok buku tidak boleh kurang dari 0.',
        ]);

        Buku::create($validated);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail buku.
     */
    public function show(Buku $buku)
    {
        $buku->load(['peminjaman.anggota']);

        return view('buku.show', compact('buku'));
    }

    /**
     * Menampilkan form edit buku.
     */
    public function edit(Buku $buku)
    {
        return view('buku.edit', compact('buku'));
    }

    /**
     * Memperbarui data buku.
     */
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul' => 'required|max:100',
            'penulis' => 'required|max:100',
            'penerbit' => 'required|max:100',
            'tahun_terbit' => 'required|integer',
            'stok' => 'required|integer|min:0',
        ], [
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.max' => 'Judul buku maksimal 100 karakter.',
            'penulis.required' => 'Penulis buku wajib diisi.',
            'penulis.max' => 'Penulis buku maksimal 100 karakter.',
            'penerbit.required' => 'Penerbit buku wajib diisi.',
            'penerbit.max' => 'Penerbit buku maksimal 100 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
            'stok.required' => 'Stok buku wajib diisi.',
            'stok.integer' => 'Stok buku harus berupa angka.',
            'stok.min' => 'Stok buku tidak boleh kurang dari 0.',
        ]);

        $buku->update($validated);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Menghapus buku dari database.
     */
    public function destroy(Buku $buku)
    {
        // Cek jika buku sedang dipinjam
        $adaPeminjamanAktif = $buku->peminjaman()->where('status', 'Dipinjam')->exists();
        if ($adaPeminjamanAktif) {
            return redirect()->route('buku.index')
                ->with('error', 'Buku tidak dapat dihapus karena sedang dalam status dipinjam.');
        }

        $buku->delete();

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil dihapus!');
    }
}
