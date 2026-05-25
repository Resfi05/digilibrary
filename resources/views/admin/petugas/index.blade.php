@extends('layouts.admin')

@section('title', 'Kelola Petugas')
@section('page-title', 'Kelola Data Petugas')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>👤 Daftar Petugas</h2>
        <a href="{{ route('admin.petugas.create') }}" class="btn btn-primary">+ Tambah Petugas</a>
    </div>
    <div class="card-body">
        <div class="filter-bar no-print" style="margin-bottom: 15px;">
            <form method="GET" action="{{ route('admin.petugas.index') }}" style="display: flex; gap: 10px; flex: 1;">
                <div class="search-box">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau email petugas...">
                </div>
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($petugas->count() > 0)
                        @foreach($petugas as $index => $p)
                            <tr>
                                <td>{{ $petugas->firstItem() + $index }}</td>
                                <td><strong>{{ $p->name }}</strong></td>
                                <td>{{ $p->email }}</td>
                                <td>{{ $p->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $p->is_active ? 'success' : 'danger' }}">
                                        {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.petugas.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="javascript:void(0)" onclick="confirmDelete('{{ route('admin.petugas.destroy', $p->id) }}')" class="btn btn-danger btn-sm">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">👤</div>
                                    <h3>Belum Ada Petugas</h3>
                                    <p>Silakan tambahkan petugas baru</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{ $petugas->links() }}
    </div>
</div>
@endsection