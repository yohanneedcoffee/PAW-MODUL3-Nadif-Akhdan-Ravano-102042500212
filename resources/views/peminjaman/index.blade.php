@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Daftar Peminjaman</h2>
        <p class="text-muted mb-0">Kelola transaksi peminjaman dan pengembalian buku.</p>
    </div>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Peminjaman
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Nama Anggota</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-3" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjaman as $index => $item)
                        <tr>
                            <td class="ps-3">{{ $peminjaman->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $item->anggota->nama ?? '-' }}</td>
                            <td>{{ $item->buku->judul ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                            <td>
                                @if($item->tanggal_kembali)
                                    {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Belum dikembalikan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status === 'Dipinjam')
                                    <span class="badge bg-secondary">Dipinjam</span>
                                @else
                                    <span class="badge bg-dark">Dikembalikan</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('peminjaman.show', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($item->status === 'Dipinjam')
                                    <form action="{{ route('peminjaman.kembali', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin buku ini sudah dikembalikan?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-primary">Kembalikan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data peminjaman buku.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($peminjaman->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $peminjaman->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
