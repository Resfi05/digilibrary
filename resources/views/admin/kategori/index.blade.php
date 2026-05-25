@extends('layouts.admin')

@section('title', 'Daftar Kategori')
@section('page-title', 'Manajemen Kategori')
@section('page-sub', 'Kelola semua kategori koleksi buku')

@push('styles')
<style>
    .kat-stats { display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px; }
    .kat-stat { background:white;border-radius:14px;padding:18px 20px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);display:flex;align-items:center;gap:14px;transition:all .25s; }
    .kat-stat:hover { transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.08); }
    .kat-stat-icon { width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0; }
    .kat-stat-num { font-size:1.5rem;font-weight:800;color:#111827;line-height:1; }
    .kat-stat-label { font-size:.75rem;color:#64748b;margin-top:4px; }
    .adm-card { background:white;border-radius:14px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04); }
    .filter-bar { display:flex;align-items:center;gap:10px;padding:16px 20px;border-bottom:1px solid #f8fafc;flex-wrap:wrap; }
    .filter-search { display:flex;align-items:center;gap:8px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 14px;flex:1;min-width:200px; }
    .filter-search input { border:none;outline:none;background:transparent;font-family:inherit;font-size:.875rem;width:100%; }
    .filter-btn { display:flex;align-items:center;gap:6px;padding:9px 16px;border-radius:10px;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer;border:1.5px solid #e2e8f0;background:white;color:#374151;text-decoration:none;transition:all .2s; }
    .filter-btn:hover { border-color:#1a56db;color:#1a56db; }
    .filter-btn-primary { background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;border:none; }
    .filter-btn-primary:hover { opacity:.9;color:white; }
    .adm-table-wrap { overflow-x:auto; }
    .adm-table { width:100%;border-collapse:collapse;font-size:.82rem; }
    .adm-table th { text-align:left;padding:12px 16px;background:#f8fafc;color:#64748b;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #f1f5f9; }
    .adm-table td { padding:14px 16px;border-bottom:1px solid #f8fafc;vertical-align:middle; }
    .adm-table tr:last-child td { border-bottom:none; }
    .adm-table tr:hover td { background:#fafbff; }
    .kat-icon { width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0; }
    .buku-bar-wrap { display:flex;align-items:center;gap:10px; }
    .buku-bar { height:8px;border-radius:99px;background:#e2e8f0;flex:1;max-width:120px;overflow:hidden; }
    .buku-bar-fill { height:100%;border-radius:99px;background:linear-gradient(135deg,#1a56db,#0e9f6e); }
    .tbl-actions { display:flex;gap:6px;align-items:center; }
    .tbl-btn { display:inline-flex;align-items:center;gap:4px;padding:6px 12px;border-radius:8px;font-family:inherit;font-size:.78rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .2s; }
    .tbl-btn-edit { background:#fef9c3;color:#a16207; }
    .tbl-btn-edit:hover { background:#fde68a; }
    .tbl-btn-del { background:#fee2e2;color:#b91c1c; }
    .tbl-btn-del:hover { background:#fca5a5; }
    .pagination-wrap { display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f8fafc;flex-wrap:wrap;gap:10px; }
    .pagination-info { font-size:.82rem;color:#64748b; }
    .pg-btns { display:flex;gap:6px; }
    .pg-btn { width:34px;height:34px;border-radius:8px;border:1.5px solid #e2e8f0;background:white;display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:600;color:#374151;text-decoration:none;transition:all .2s; }
    .pg-btn:hover { border-color:#1a56db;color:#1a56db; }
    .pg-btn.active { background:#1a56db;color:white;border-color:#1a56db; }
    .pg-btn.disabled { opacity:.4;pointer-events:none; }
    .breadcrumb { display:flex;align-items:center;gap:8px;font-size:.82rem;color:#94a3b8;margin-bottom:16px; }
    .breadcrumb a { color:#1a56db;text-decoration:none; }

    /* MODAL */
    .modal-overlay { position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(4px);z-index:1000;display:none;align-items:center;justify-content:center;padding:20px; }
    .modal-overlay.show { display:flex; }
    .modal-box { background:white;border-radius:20px;padding:28px;width:100%;max-width:440px;box-shadow:0 24px 64px rgba(0,0,0,.15);animation:slideUp .25s ease; }
    @keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .modal-header { display:flex;align-items:center;justify-content:space-between;margin-bottom:20px; }
    .modal-header h3 { font-size:1.05rem;font-weight:700;color:#111827; }
    .modal-close { width:30px;height:30px;border-radius:8px;border:1.5px solid #e5e7eb;background:white;cursor:pointer;font-size:.9rem;display:flex;align-items:center;justify-content:center;transition:all .2s; }
    .modal-close:hover { background:#fee2e2;border-color:#fecaca;color:#dc2626; }
    .modal-form { display:flex;flex-direction:column;gap:14px; }
    .mf-group { display:flex;flex-direction:column;gap:5px; }
    .mf-label { font-size:.82rem;font-weight:600;color:#374151; }
    .mf-input { padding:10px 14px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:.875rem;outline:none;transition:all .25s;background:#fafafa;width:100%; }
    .mf-input:focus { border-color:#1a56db;background:white;box-shadow:0 0 0 3px rgba(26,86,219,.08); }
    .modal-actions { display:flex;gap:10px;margin-top:4px; }
    .modal-btn-cancel { flex:1;padding:11px;border-radius:10px;border:1.5px solid #e5e7eb;background:white;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer; }
    .modal-btn-save { flex:2;padding:11px;border-radius:10px;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;border:none;font-family:inherit;font-size:.875rem;font-weight:700;cursor:pointer; }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <span>›</span><span>Daftar Kategori</span>
</div>

{{-- STATS --}}
<div class="kat-stats">
    <div class="kat-stat">
        <div class="kat-stat-icon" style="background:#ede9fe">📂</div>
        <div>
            <div class="kat-stat-num">{{ $stats['total'] }}</div>
            <div class="kat-stat-label">Total Kategori</div>
        </div>
    </div>
    <div class="kat-stat">
        <div class="kat-stat-icon" style="background:#dcfce7">✅</div>
        <div>
            <div class="kat-stat-num">{{ $stats['ada_buku'] }}</div>
            <div class="kat-stat-label">Ada Buku</div>
        </div>
    </div>
    <div class="kat-stat">
        <div class="kat-stat-icon" style="background:#fee2e2">📭</div>
        <div>
            <div class="kat-stat-num">{{ $stats['kosong'] }}</div>
            <div class="kat-stat-label">Kosong</div>
        </div>
    </div>
    <div class="kat-stat">
        <div class="kat-stat-icon" style="background:#dbeafe">📚</div>
        <div>
            <div class="kat-stat-num">{{ $stats['total_buku'] }}</div>
            <div class="kat-stat-label">Total Buku</div>
        </div>
    </div>
</div>

{{-- TABEL --}}
<div class="adm-card">
    <div class="filter-bar">
        <form action="{{ route('admin.kategori.index') }}" method="GET"
            style="display:flex;gap:10px;flex:1;flex-wrap:wrap;align-items:center">
            <div class="filter-search" style="flex:1;min-width:200px">
                <span>🔍</span>
                <input type="text" name="search" placeholder="Cari nama kategori..."
                    value="{{ request('search') }}">
            </div>
            <button type="submit" class="filter-btn">🔍 Cari</button>
            @if(request('search'))
            <a href="{{ route('admin.kategori.index') }}" class="filter-btn">Reset</a>
            @endif
        </form>
        <button onclick="openModal('modalTambah')" class="filter-btn filter-btn-primary">
            ＋ Tambah Kategori
        </button>
    </div>

    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Buku</th>
                    <th>Proporsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $katColors = ['#ede9fe','#dbeafe','#dcfce7','#fef3c7','#fce7f3','#ffedd5','#e0f2fe','#f3e8ff','#ecfdf5','#fff7ed'];
                    $katIcons  = ['📂','📁','🗂️','📋','📌','📎','🏷️','🔖','📑','📃'];
                    $maxBuku   = $kategori->max('books_count') ?: 1;
                @endphp
                @forelse($kategori as $i => $k)
                <tr>
                    <td style="color:#94a3b8">{{ $kategori->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="kat-icon" style="background:{{ $katColors[$i % 10] }}">
                                {{ $katIcons[$i % 10] }}
                            </div>
                            <div>
                                <div style="font-weight:700;color:#111827">{{ $k->nama_kategori }}</div>
                                <div style="font-size:.7rem;color:#94a3b8">ID: #{{ $k->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:#64748b;font-size:.82rem">{{ $k->deskripsi ?? '-' }}</td>
                    <td>
                        <span style="font-weight:700;color:#1a56db">{{ $k->books_count }}</span>
                        <span style="font-size:.75rem;color:#94a3b8"> buku</span>
                    </td>
                    <td>
                        <div class="buku-bar-wrap">
                            <div class="buku-bar">
                                <div class="buku-bar-fill"
                                    style="width:{{ $maxBuku > 0 ? round($k->books_count/$maxBuku*100) : 0 }}%">
                                </div>
                            </div>
                            <span style="font-size:.72rem;color:#94a3b8;min-width:30px">
                                {{ $maxBuku > 0 ? round($k->books_count/$maxBuku*100) : 0 }}%
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="tbl-actions">
                            <button class="tbl-btn tbl-btn-edit"
                                onclick="editKategori({{ $k->id }}, '{{ addslashes($k->nama_kategori) }}', '{{ addslashes($k->deskripsi ?? '') }}')">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="tbl-btn tbl-btn-del"
                                    onclick="return confirm('Hapus kategori {{ addslashes($k->nama_kategori) }}?{{ $k->books_count > 0 ? ' Kategori ini masih memiliki '.$k->books_count.' buku!' : '' }}')">
                                    🗑 Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:48px;color:#94a3b8">
                        <div style="font-size:2.5rem;margin-bottom:10px">📂</div>
                        <div style="font-weight:600;margin-bottom:6px">Belum ada kategori</div>
                        <div style="font-size:.82rem;margin-bottom:14px">Tambahkan kategori untuk mengelompokkan buku</div>
                        <button onclick="openModal('modalTambah')"
                            style="padding:10px 20px;border-radius:10px;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;border:none;font-family:inherit;font-size:.875rem;font-weight:700;cursor:pointer">
                            ＋ Tambah Kategori
                        </button>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $kategori->firstItem() ?? 0 }}-{{ $kategori->lastItem() ?? 0 }}
            dari {{ $kategori->total() }} kategori
        </div>
        <div class="pg-btns">
            @if($kategori->onFirstPage())
                <span class="pg-btn disabled">‹</span>
            @else
                <a href="{{ $kategori->previousPageUrl() }}" class="pg-btn">‹</a>
            @endif
            @foreach($kategori->getUrlRange(1, $kategori->lastPage()) as $page => $url)
                @if($page == $kategori->currentPage())
                    <span class="pg-btn active">{{ $page }}</span>
                @elseif($page==1 || $page==$kategori->lastPage() || abs($page-$kategori->currentPage())<=1)
                    <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                @elseif(abs($page-$kategori->currentPage())==2)
                    <span class="pg-btn disabled">...</span>
                @endif
            @endforeach
            @if($kategori->hasMorePages())
                <a href="{{ $kategori->nextPageUrl() }}" class="pg-btn">›</a>
            @else
                <span class="pg-btn disabled">›</span>
            @endif
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal-overlay" id="modalTambah">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>➕ Tambah Kategori</h3>
            <button onclick="closeModal('modalTambah')" class="modal-close">✕</button>
        </div>
        <form action="{{ route('admin.kategori.store') }}" method="POST" class="modal-form">
            @csrf
            <div class="mf-group">
                <label class="mf-label">Nama Kategori *</label>
                <input type="text" name="nama_kategori" class="mf-input"
                    placeholder="Contoh: Fiksi, Sains, Sejarah..." required autofocus>
            </div>
            <div class="mf-group">
                <label class="mf-label">Deskripsi <span style="color:#94a3b8;font-weight:400">(opsional)</span></label>
                <textarea name="deskripsi" class="mf-input" rows="3"
                    placeholder="Deskripsi singkat kategori..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeModal('modalTambah')" class="modal-btn-cancel">Batal</button>
                <button type="submit" class="modal-btn-save">➕ Tambah</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>✏️ Edit Kategori</h3>
            <button onclick="closeModal('modalEdit')" class="modal-close">✕</button>
        </div>
        <form id="editForm" method="POST" class="modal-form">
            @csrf @method('PUT')
            <div class="mf-group">
                <label class="mf-label">Nama Kategori *</label>
                <input type="text" id="editNama" name="nama_kategori" class="mf-input" required>
            </div>
            <div class="mf-group">
                <label class="mf-label">Deskripsi <span style="color:#94a3b8;font-weight:400">(opsional)</span></label>
                <textarea id="editDeskripsi" name="deskripsi" class="mf-input" rows="3"></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeModal('modalEdit')" class="modal-btn-cancel">Batal</button>
                <button type="submit" class="modal-btn-save">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('show');
    document.body.style.overflow = '';
}
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});

function editKategori(id, nama, deskripsi) {
    document.getElementById('editForm').action = '/admin/kategori/' + id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editDeskripsi').value = deskripsi;
    openModal('modalEdit');
}

@if(session('success') || $errors->any())
// Auto-buka modal edit jika ada error saat edit
@endif
</script>
@endpush