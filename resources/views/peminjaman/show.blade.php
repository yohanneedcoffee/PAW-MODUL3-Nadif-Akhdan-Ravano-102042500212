@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-1">Detail Peminjaman</h3>
                <p class="text-muted small mb-0">Rincian transaksi peminjaman buku.</p>
            </div>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom">
                    <div>
                        <span class="text-muted small d-block">ID Transaksi</span>
                        <h5 class="fw-bold mb-0">#TRX-{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}</h5>
                    </div>
                    <div>
                        @if($peminjaman->status === 'Dipinjam')
                            <span class="badge bg-secondary px-3 py-2 fs-6">
                                Dipinjam
                            </span>
                        @else
                            <span class="badge bg-dark px-3 py-2 fs-6">
                                Dikembalikan
                            </span>
                        @endif
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-4 fw-semibold text-muted">Nama Anggota</div>
                    <div class="col-sm-8 text-dark">{{ $peminjaman->anggota->nama ?? '-' }} ({{ $peminjaman->anggota->email ?? '-' }})</div>

                    <div class="col-sm-4 fw-semibold text-muted">Judul Buku</div>
                    <div class="col-sm-8 text-dark">{{ $peminjaman->buku->judul ?? '-' }}</div>

                    <div class="col-sm-4 fw-semibold text-muted">Penulis & Penerbit</div>
                    <div class="col-sm-8 text-dark">{{ $peminjaman->buku->penulis ?? '-' }} / {{ $peminjaman->buku->penerbit ?? '-' }}</div>

                    <div class="col-sm-4 fw-semibold text-muted">Tanggal Pinjam</div>
                    <div class="col-sm-8 text-dark">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}</div>

                    <div class="col-sm-4 fw-semibold text-muted">Tanggal Kembali</div>
                    <div class="col-sm-8 text-dark">
                        @if($peminjaman->tanggal_kembali)
                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->translatedFormat('d F Y') }}
                        @else
                            <span class="text-muted" >Belum dikembalikan</span>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    @if($peminjaman->status === 'Dipinjam')
                        <form action="{{ route('peminjaman.kembali', $peminjaman->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin buku ini sudah dikembalikan?')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-primary px-3">
                                Kembalikan Buku
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-light border px-4">Kembali ke Daftar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
