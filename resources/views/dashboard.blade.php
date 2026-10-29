@extends('layouts.app')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="fw-bold mb-1">Dashboard Perpustakaan</h2>
        <p class="text-muted mb-0">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>. Berikut ringkasan operasional perpustakaan.</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Transaksi Peminjaman Baru
        </a>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 shadow-sm bg-white">
            <span class="text-muted small d-block mb-1">Total Koleksi Buku</span>
            <h3 class="fw-bold mb-0 text-dark">{{ $totalBuku }}</h3>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 shadow-sm bg-white">
            <span class="text-muted small d-block mb-1">Total Anggota</span>
            <h3 class="fw-bold mb-0 text-dark">{{ $totalAnggota }}</h3>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 shadow-sm bg-white">
            <span class="text-muted small d-block mb-1">Sedang Dipinjam</span>
            <h3 class="fw-bold mb-0 text-dark">{{ $totalPeminjamanAktif }}</h3>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 shadow-sm bg-white">
            <span class="text-muted small d-block mb-1">Sudah Dikembalikan</span>
            <h3 class="fw-bold mb-0 text-dark">{{ $totalPeminjamanSelesai }}</h3>
        </div>
    </div>
</div>

<!-- Riwayat Peminjaman Terbaru -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">5 Transaksi Peminjaman Terbaru</h5>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Nama Anggota</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamanTerbaru as $item)
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $item->anggota->nama ?? '-' }}</td>
                            <td>{{ $item->buku->judul ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d F Y') }}</td>
                            <td>
                                @if($item->tanggal_kembali)
                                    {{ \Carbon\Carbon::parse($item->tanggal_kembali)->translatedFormat('d F Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status === 'Dipinjam')
                                    <span class="badge bg-secondary">Dipinjam</span>
                                @else
                                    <span class="badge bg-dark">Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Belum ada riwayat transaksi peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
