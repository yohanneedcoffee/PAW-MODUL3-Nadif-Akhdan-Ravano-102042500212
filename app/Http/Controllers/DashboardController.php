<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama.
     */
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $totalPeminjamanAktif = Peminjaman::where('status', 'Dipinjam')->count();
        $totalPeminjamanSelesai = Peminjaman::where('status', 'Dikembalikan')->count();

        $peminjamanTerbaru = Peminjaman::with(['anggota', 'buku'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'totalPeminjamanAktif',
            'totalPeminjamanSelesai',
            'peminjamanTerbaru'
        ));
    }
}
