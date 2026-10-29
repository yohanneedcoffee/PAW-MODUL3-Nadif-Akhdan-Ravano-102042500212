<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    /**
     * Menampilkan daftar semua anggota.
     */
    public function index()
    {
        $anggota = Anggota::latest()->paginate(10);

        return view('anggota.index', compact('anggota'));
    }

    /**
     * Menampilkan form tambah anggota baru.
     */
    public function create()
    {
        return view('anggota.create');
    }

    /**
     * Menyimpan anggota baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:100',
            'email' => 'required|email|max:100|unique:anggota,email',
            'no_hp' => 'required|max:15',
        ], [
            'nama.required' => 'Nama anggota wajib diisi.',
            'nama.max' => 'Nama anggota maksimal 100 karakter.',
            'email.required' => 'Email anggota wajib diisi.',
            'email.email' => 'Format email anggota tidak valid.',
            'email.max' => 'Email anggota maksimal 100 karakter.',
            'email.unique' => 'Email ini sudah digunakan oleh anggota lain.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.max' => 'Nomor HP maksimal 15 karakter.',
        ]);

        Anggota::create($validated);

        return redirect()->route('anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail anggota.
     */
    public function show(Anggota $anggota)
    {
        $anggota->load(['peminjaman.buku']);

        return view('anggota.show', compact('anggota'));
    }

    /**
     * Menampilkan form edit anggota.
     */
    public function edit(Anggota $anggota)
    {
        return view('anggota.edit', compact('anggota'));
    }

    /**
     * Memperbarui data anggota.
     */
    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'nama' => 'required|max:100',
            'email' => 'required|email|max:100|unique:anggota,email,' . $anggota->id,
            'no_hp' => 'required|max:15',
        ], [
            'nama.required' => 'Nama anggota wajib diisi.',
            'nama.max' => 'Nama anggota maksimal 100 karakter.',
            'email.required' => 'Email anggota wajib diisi.',
            'email.email' => 'Format email anggota tidak valid.',
            'email.max' => 'Email anggota maksimal 100 karakter.',
            'email.unique' => 'Email ini sudah digunakan oleh anggota lain.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.max' => 'Nomor HP maksimal 15 karakter.',
        ]);

        $anggota->update($validated);

        return redirect()->route('anggota.index')
            ->with('success', 'Anggota berhasil diperbarui!');
    }

    /**
     * Menghapus anggota dari database.
     */
    public function destroy(Anggota $anggota)
    {
        // Cek jika anggota masih memiliki pinjaman aktif
        $adaPinjamanAktif = $anggota->peminjaman()->where('status', 'Dipinjam')->exists();
        if ($adaPinjamanAktif) {
            return redirect()->route('anggota.index')
                ->with('error', 'Anggota tidak dapat dihapus karena masih meminjam buku.');
        }

        $anggota->delete();

        return redirect()->route('anggota.index')
            ->with('success', 'Anggota berhasil dihapus!');
    }
}
