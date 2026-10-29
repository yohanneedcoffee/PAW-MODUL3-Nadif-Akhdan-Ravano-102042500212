@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Daftar Anggota</h2>
        <p class="text-muted mb-0">Kelola data keanggotaan perpustakaan.</p>
    </div>
    <a href="{{ route('anggota.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Tambah Anggota
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
                        <th>Email</th>
                        <th>Nomor HP</th>
                        <th class="text-end pe-3" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggota as $index => $item)
                        <tr>
                            <td class="ps-3 text-muted">{{ $anggota->firstItem() + $index }}</td>
                            <td class="fw-semibold text-dark">{{ $item->nama }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->no_hp }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('anggota.show', $item->id) }}" class="btn btn-sm btn-outline-secondary me-1" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('anggota.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('anggota.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">
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
                            <td colspan="5" class="text-center py-4 text-muted">
                                Belum ada data anggota tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($anggota->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $anggota->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
