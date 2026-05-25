@extends('layouts.admin')

@section('title', 'Daftar User')
@section('page-title', 'Daftar User')
@section('page-sub', 'Kelola semua akun anggota perpustakaan')

@push('styles')
<style>
    .user-stats { display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px; }
    .user-stat-card { background:white;border-radius:14px;padding:20px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);display:flex;align-items:center;gap:16px;transition:all .25s; }
    .user-stat-card:hover { transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.08); }
    .user-stat-icon { width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0; }
    .user-stat-num { font-size:1.6rem;font-weight:800;color:#111827;line-height:1; }
    .user-stat-label { font-size:.78rem;color:#64748b;margin-top:4px; }
    .adm-card { background:white;border-radius:14px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04); }
    .filter-bar { display:flex;align-items:center;gap:10px;padding:16px 20px;border-bottom:1px solid #f8fafc;flex-wrap:wrap; }
    .filter-search { display:flex;align-items:center;gap:8px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 14px;flex:1;min-width:180px; }
    .filter-search input { border:none;outline:none;background:transparent;font-family:inherit;font-size:.875rem;width:100%; }
    .filter-select { padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:.875rem;color:#374151;background:white;outline:none;cursor:pointer; }
    .filter-btn { display:flex;align-items:center;gap:6px;padding:9px 16px;border-radius:10px;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer;border:1.5px solid #e2e8f0;background:white;color:#374151;text-decoration:none;transition:all .2s; }
    .filter-btn:hover { border-color:#1a56db;color:#1a56db; }
    .adm-table-wrap { overflow-x:auto; }
    .adm-table { width:100%;border-collapse:collapse;font-size:.82rem; }
    .adm-table th { text-align:left;padding:12px 16px;background:#f8fafc;color:#64748b;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #f1f5f9; }
    .adm-table td { padding:14px 16px;border-bottom:1px solid #f8fafc;vertical-align:middle; }
    .adm-table tr:last-child td { border-bottom:none; }
    .adm-table tr:hover td { background:#fafbff; }
    .adm-table input[type="checkbox"] { width:15px;height:15px;accent-color:#1a56db;cursor:pointer; }
    .tbl-avatar { width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0; }
    .tbl-avatar-placeholder { width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:700;flex-shrink:0; }
    .status-badge { padding:4px 10px;border-radius:99px;font-size:.72rem;font-weight:700; }
    .status-aktif { background:#dcfce7;color:#15803d; }
    .status-nonaktif { background:#fee2e2;color:#b91c1c; }
    .tbl-actions { display:flex;gap:6px;align-items:center; }
    .tbl-btn { width:30px;height:30px;border-radius:8px;border:1.5px solid #e2e8f0;background:white;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.85rem;transition:all .2s;color:#374151; }
    .tbl-btn:hover { transform:scale(1.1); }
    .tbl-btn-view:hover { border-color:#1a56db;color:#1a56db;background:#eff6ff; }
    .tbl-btn-edit:hover { border-color:#f59e0b;color:#d97706;background:#fef9c3; }
    .tbl-btn-del:hover { border-color:#ef4444;color:#ef4444;background:#fee2e2; }
    .tbl-btn-block:hover { border-color:#6b7280;color:#374151;background:#f3f4f6; }
    .pagination-wrap { display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f8fafc;flex-wrap:wrap;gap:10px; }
    .pagination-info { font-size:.82rem;color:#64748b; }
    .pagination-btns { display:flex;gap:6px; }
    .pg-btn { width:34px;height:34px;border-radius:8px;border:1.5px solid #e2e8f0;background:white;display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:600;color:#374151;text-decoration:none;transition:all .2s; }
    .pg-btn:hover { border-color:#1a56db;color:#1a56db; }
    .pg-btn.active { background:#1a56db;color:white;border-color:#1a56db; }
    .pg-btn.disabled { opacity:.4;pointer-events:none; }
    .rs-card { background:white;border-radius:14px;padding:20px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);margin-bottom:16px; }
    .rs-title { font-size:.9rem;font-weight:700;color:#111827;margin-bottom:14px; }
    .status-list { display:flex;flex-direction:column;gap:10px; }
    .status-row { display:flex;align-items:center;justify-content:space-between;font-size:.82rem; }
    .info-box { background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:14px;font-size:.8rem;color:#1e40af;line-height:1.6; }
    .breadcrumb { display:flex;align-items:center;gap:8px;font-size:.82rem;color:#94a3b8;margin-bottom:16px; }
    .breadcrumb a { color:#1a56db;text-decoration:none; }
    .modal-overlay { position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(4px);z-index:1000;display:none;align-items:center;justify-content:center;padding:20px; }
    .modal-overlay.show { display:flex; }
    .modal-box { background:white;border-radius:20px;padding:28px;width:100%;max-width:460px;box-shadow:0 24px 64px rgba(0,0,0,.15);animation:slideUp .25s ease; }
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
    @media(max-width:900px) { .user-stats{grid-template-columns:repeat(2,1fr);} }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <span>›</span><span>Daftar User</span>
</div>

<div class="user-stats">
    <div class="user-stat-card">
        <div class="user-stat-icon" style="background:#dbeafe">👥</div>
        <div>
            <div class="user-stat-num">{{ number_format($stats['total']) }}</div>
            <div class="user-stat-label">Total User</div>
        </div>
    </div>
    <div class="user-stat-card">
        <div class="user-stat-icon" style="background:#dcfce7">✅</div>
        <div>
            <div class="user-stat-num">{{ number_format($stats['aktif']) }}</div>
            <div class="user-stat-label">Akun Aktif</div>
        </div>
    </div>
    <div class="user-stat-card">
        <div class="user-stat-icon" style="background:#fee2e2">❌</div>
        <div>
            <div class="user-stat-num">{{ number_format($stats['nonaktif']) }}</div>
            <div class="user-stat-label">Akun Nonaktif</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 260px;gap:20px">
    <div class="adm-card">
        <div class="filter-bar">
            <form action="{{ route('admin.user.index') }}" method="GET"
                style="display:flex;gap:10px;flex:1;flex-wrap:wrap;align-items:center">
                <div class="filter-search" style="flex:1;min-width:180px">
                    <span>🔍</span>
                    <input type="text" name="search" placeholder="Cari nama atau email..."
                        value="{{ request('search') }}">
                </div>
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="semua"    {{ request('status','semua')==='semua'    ? 'selected':'' }}>Semua Status</option>
                    <option value="aktif"    {{ request('status')==='aktif'    ? 'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status')==='nonaktif' ? 'selected':'' }}>Nonaktif</option>
                </select>
                <button type="submit" class="filter-btn">🔍 Filter</button>
                @if(request('search') || request('status'))
                <a href="{{ route('admin.user.index') }}" class="filter-btn">Reset</a>
                @endif
            </form>
        </div>

        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                    <tr>
                        <td><input type="checkbox" class="row-check"></td>
                        <td style="color:#94a3b8">{{ $users->firstItem() + $i }}</td>
                        <td>
                            @if($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}" class="tbl-avatar" alt="">
                            @else
                                <div class="tbl-avatar-placeholder">{{ strtoupper(substr($user->name,0,1)) }}</div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:600;color:#111827">{{ $user->name }}</div>
                            <div style="font-size:.72rem;color:#94a3b8">{{ $user->phone ?? '-' }}</div>
                        </td>
                        <td style="color:#374151">{{ $user->email }}</td>
                        <td>
                            <span class="status-badge {{ $user->is_active ? 'status-aktif':'status-nonaktif' }}">
                                {{ $user->is_active ? 'Aktif':'Nonaktif' }}
                            </span>
                        </td>
                        <td style="color:#64748b;font-size:.78rem">{{ $user->created_at?->format('d M Y') }}</td>
                        <td>
                            <div class="tbl-actions">
                                <button class="tbl-btn tbl-btn-view" title="Detail"
                                    onclick="lihatUser('{{ addslashes($user->name) }}','{{ $user->email }}','{{ $user->phone ?? '' }}','{{ $user->address ?? '' }}','{{ $user->created_at?->format('d M Y') }}')">
                                    👁
                                </button>
                                <button class="tbl-btn tbl-btn-edit" title="Edit"
                                    onclick="editUser({{ $user->id }},'{{ addslashes($user->name) }}','{{ $user->email }}')">
                                    ✏️
                                </button>
                                <form action="{{ route('admin.user.toggle', $user->id) }}" method="POST" style="display:inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="tbl-btn tbl-btn-block"
                                        title="{{ $user->is_active ? 'Nonaktifkan':'Aktifkan' }}"
                                        onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan':'Aktifkan' }} akun {{ addslashes($user->name) }}?')">
                                        {{ $user->is_active ? '🔒':'🔓' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="tbl-btn tbl-btn-del" title="Hapus"
                                        onclick="return confirm('Hapus user {{ addslashes($user->name) }}?')">
                                        🗑
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:40px;color:#94a3b8">
                            <div style="font-size:2rem;margin-bottom:8px">👥</div>
                            Tidak ada user ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            <div class="pagination-info">
                Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
            </div>
            <div class="pagination-btns">
                @if($users->onFirstPage())
                    <span class="pg-btn disabled">‹</span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="pg-btn">‹</a>
                @endif
                @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if($page == $users->currentPage())
                        <span class="pg-btn active">{{ $page }}</span>
                    @elseif($page==1 || $page==$users->lastPage() || abs($page-$users->currentPage())<=1)
                        <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                    @elseif(abs($page-$users->currentPage())==2)
                        <span class="pg-btn disabled">...</span>
                    @endif
                @endforeach
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="pg-btn">›</a>
                @else
                    <span class="pg-btn disabled">›</span>
                @endif
            </div>
        </div>
    </div>

    <div>
        <div class="rs-card">
            <div class="rs-title">Status Akun</div>
            <div class="status-list">
                <div class="status-row">
                    <span>✅ Aktif</span>
                    <span style="font-weight:700;color:#15803d">{{ $stats['aktif'] }}</span>
                </div>
                <div class="status-row">
                    <span>❌ Nonaktif</span>
                    <span style="font-weight:700;color:#b91c1c">{{ $stats['nonaktif'] }}</span>
                </div>
                <div class="status-row" style="padding-top:8px;border-top:1px solid #f3f4f6;margin-top:4px">
                    <span><strong>Total</strong></span>
                    <span style="font-weight:700">{{ $stats['total'] }}</span>
                </div>
            </div>
        </div>
        <div class="rs-card">
            <div class="rs-title">Informasi</div>
            <div class="info-box">
                ℹ️ User dengan status nonaktif tidak dapat login. Aktifkan kembali kapan saja dengan tombol 🔓.
            </div>
        </div>
        <div class="rs-card">
            <div class="rs-title">Navigasi Cepat</div>
            <a href="{{ route('admin.petugas.list') }}"
                style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f8fafc;border-radius:10px;text-decoration:none;color:#374151;font-size:.875rem;font-weight:600;transition:all .2s"
                onmouseover="this.style.background='#eff6ff';this.style.color='#1a56db'"
                onmouseout="this.style.background='#f8fafc';this.style.color='#374151'">
                👨‍💼 Lihat Daftar Petugas →
            </a>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>✏️ Edit User</h3>
            <button onclick="closeModal('modalEdit')" class="modal-close">✕</button>
        </div>
        <form id="editForm" method="POST" class="modal-form">
            @csrf @method('PUT')
            <div class="mf-group">
                <label class="mf-label">Nama Lengkap *</label>
                <input type="text" id="editName" name="name" class="mf-input" required>
            </div>
            <div class="mf-group">
                <label class="mf-label">Email *</label>
                <input type="email" id="editEmail" name="email" class="mf-input" required>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeModal('modalEdit')" class="modal-btn-cancel">Batal</button>
                <button type="submit" class="modal-btn-save">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal-overlay" id="modalDetail">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>👁 Detail User</h3>
            <button onclick="closeModal('modalDetail')" class="modal-close">✕</button>
        </div>
        <div id="detailContent"></div>
        <div style="margin-top:16px">
            <button onclick="closeModal('modalDetail')" class="modal-btn-cancel" style="width:100%">Tutup</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('show'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow=''; }

document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', function(e) { if(e.target===this) closeModal(this.id); });
});

document.getElementById('checkAll').addEventListener('change', function() {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
});

function editUser(id, name, email) {
    document.getElementById('editForm').action = '/admin/user/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    openModal('modalEdit');
}

function lihatUser(name, email, phone, address, joined) {
    document.getElementById('detailContent').innerHTML = `
        <div style="display:flex;align-items:center;gap:14px;padding:14px;background:#f8fafc;border-radius:12px;margin-bottom:12px">
            <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:700">
                ${name.charAt(0).toUpperCase()}
            </div>
            <div>
                <div style="font-size:1rem;font-weight:700;color:#111827">${name}</div>
                <div style="font-size:.82rem;color:#6b7280">${email}</div>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
            <div style="padding:10px 14px;background:#f8fafc;border-radius:10px">
                <div style="font-size:.72rem;color:#94a3b8;margin-bottom:3px">TELEPON</div>
                <div style="font-size:.875rem;font-weight:600;color:#111827">${phone||'-'}</div>
            </div>
            <div style="padding:10px 14px;background:#f8fafc;border-radius:10px">
                <div style="font-size:.72rem;color:#94a3b8;margin-bottom:3px">BERGABUNG</div>
                <div style="font-size:.875rem;font-weight:600;color:#111827">${joined}</div>
            </div>
            <div style="padding:10px 14px;background:#f8fafc;border-radius:10px;grid-column:1/-1">
                <div style="font-size:.72rem;color:#94a3b8;margin-bottom:3px">ALAMAT</div>
                <div style="font-size:.875rem;font-weight:600;color:#111827">${address||'-'}</div>
            </div>
        </div>
    `;
    openModal('modalDetail');
}
</script>
@endpush