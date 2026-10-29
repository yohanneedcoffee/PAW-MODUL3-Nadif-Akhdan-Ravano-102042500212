@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Daftar Buku</h2>
        <p class="text-muted mb-0">Kelola data koleksi buku perpustakaan.</p>
    </div>
    <a href="{{ route('buku.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Buku
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Stok</th>
                        <th class="text-end pe-3" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buku as $index => $item)
                        <tr>
                            <td class="ps-3 text-muted">{{ $buku->firstItem() + $index }}</td>
                            <td class="fw-semibold text-dark">{{ $item->judul }}</td>
                            <td>{{ $item->penulis }}</td>
                            <td>{{ $item->penerbit }}</td>
                            <td class="text-center">{{ $item->tahun_terbit }}</td>
                            <td class="text-center">
                                @if($item->stok > 0)
                                    <span class="badge bg-secondary px-2 py-1">{{ $item->stok }}</span>
                                @else
                                    <span class="badge bg-dark px-2 py-1">Habis (0)</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('buku.show', $item->id) }}" class="btn btn-sm btn-outline-secondary me-1" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('buku.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('buku.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Belum ada data buku tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($buku->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $buku->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
