@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-1">Detail Buku</h3>
                <p class="text-muted small mb-0">Informasi lengkap dan riwayat peminjaman buku ini.</p>
            </div>
            <div>
                <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-primary btn-sm me-1">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <h4 class="fw-bold text-dark mb-2">{{ $buku->judul }}</h4>
                        <div class="row text-secondary g-2 mt-2">
                            <div class="col-sm-4 fw-semibold text-muted">Penulis</div>
                            <div class="col-sm-8 text-dark">{{ $buku->penulis }}</div>

                            <div class="col-sm-4 fw-semibold text-muted">Penerbit</div>
                            <div class="col-sm-8 text-dark">{{ $buku->penerbit }}</div>

                            <div class="col-sm-4 fw-semibold text-muted">Tahun Terbit</div>
                            <div class="col-sm-8 text-dark">{{ $buku->tahun_terbit }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center d-flex flex-column justify-content-center">
                        <span class="text-muted small mb-1">Status Ketersediaan Stok</span>
                        <h2 class="fw-bold mb-1 text-dark">
                            {{ $buku->stok }} <span class="fs-6 text-muted">eksemplar</span>
                        </h2>
                        @if($buku->stok > 0)
                            <span class="badge bg-secondary mx-auto px-3 py-2">Tersedia untuk dipinjam</span>
                        @else
                            <span class="badge bg-secondary mx-auto px-3 py-2">Stok Habis</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Peminjaman Buku Ini -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Riwayat Peminjaman Buku Ini</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Nama Anggota</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($buku->peminjaman as $pinjam)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $pinjam->anggota->nama ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->translatedFormat('d F Y') }}</td>
                                    <td>
                                        @if($pinjam->tanggal_kembali)
                                            {{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->translatedFormat('d F Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pinjam->status === 'Dipinjam')
                                            <span class="badge bg-secondary">Dipinjam</span>
                                        @else
                                            <span class="badge bg-dark">Dikembalikan</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Buku ini belum pernah dipinjam.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
