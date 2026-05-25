@extends('layouts.petugas')

@section('title', 'Kelola Peminjaman')
@section('page-title', 'Kelola Peminjaman & Pengembalian')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>🔄 Daftar Peminjaman</h2>
    </div>
    <div class="card-body">
        <div class="filter-bar no-print" style="margin-bottom: 15px;">
            <form method="GET" action="{{ route('petugas.peminjaman.index') }}" style="display: flex; gap: 10px; flex: 1; flex-wrap: wrap;">
                <div class="search-box">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama peminjam atau judul buku...">
                </div>
                <select name="status" class="form-control" style="width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="dipinjam" {{ $status === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ $status === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="terlambat" {{ $status === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
                <input type="date" name="date_from" class="form-control" style="width: 150px;" value="{{ $dateFrom ?? '' }}">
                <input type="date" name="date_to" class="form-control" style="width: 150px;" value="{{ $dateTo ?? '' }}">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-outline">Reset</a>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tgl. Pinjam</th>
                        <th>Tgl. Kembali</th>
                        <th>Tgl. Dikembalikan</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($peminjaman->count() > 0)
                        @foreach($peminjaman as $index => $p)
                            <tr>
                                <td>{{ $peminjaman->firstItem() + $index }}</td>
                                <td><strong>{{ $p->user->name }}</strong></td>
                                <td>{{ $p->book->title }}</td>
                                <td>{{ $p->tanggal_pinjam ? date('d/m/Y', strtotime($p->tanggal_pinjam)) : '-' }}</td>
                                <td>{{ $p->tanggal_kembali ? date('d/m/Y', strtotime($p->tanggal_kembali)) : '-' }}</td>
                                <td>{{ $p->tanggal_dikembalikan ? date('d/m/Y', strtotime($p->tanggal_dikembalikan)) : '-' }}</td>
                                <td>
                                    @php $denda = $p->hitungDenda(); @endphp
                                    <span class="badge badge-{{ $denda > 0 ? 'danger' : 'success' }}">
                                        Rp {{ number_format($denda, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $p->status == 'dipinjam' ? 'success' : ($p->status == 'pending' ? 'warning' : ($p->status == 'dikembalikan' ? 'info' : ($p->status == 'terlambat' ? 'danger' : 'secondary'))) }}">
                                        {{ strtoupper($p->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        @if($p->status == 'pending')
                                            <form action="{{ route('petugas.peminjaman.approve', $p->id) }}" method="POST" style="display: inline;">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Setujui peminjaman ini?')">✓ Setujui</button>
                                            </form>
                                            <form action="{{ route('petugas.peminjaman.reject', $p->id) }}" method="POST" style="display: inline;">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak peminjaman ini?')">✗ Tolak</button>
                                            </form>
                                        @elseif($p->status == 'dipinjam' || $p->status == 'terlambat')
                                            <form action="{{ route('petugas.peminjaman.return', $p->id) }}" method="POST" style="display: inline;">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-info btn-sm" onclick="return confirm('Proses pengembalian buku ini?')">📥 Kembalikan</button>
                                            </form>
                                        @else
                                            <span style="font-size: 12px; color: #6b7280;">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon">🔄</div>
                                    <h3>Belum Ada Peminjaman</h3>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{ $peminjaman->links() }}
    </div>
</div>
@endsection