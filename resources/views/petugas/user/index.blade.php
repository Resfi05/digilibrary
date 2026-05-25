@extends('layouts.petugas')

@section('title', 'Data User')
@section('page-title', 'Data Pengguna')

@section('content')
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <div class="section-card" style="margin:0;">
        <div class="section-body" style="padding:20px;display:flex;align-items:center;gap:16px;">
            <div style="width:48px;height:48px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;">👥</div>
            <div>
                <div style="font-size:12px;color:#6b7280;">Total Pengguna</div>
                <div style="font-size:22px;font-weight:700;color:#111827;">{{ $totalUsers }}</div>
            </div>
        </div>
    </div>
    <div class="section-card" style="margin:0;">
        <div class="section-body" style="padding:20px;display:flex;align-items:center;gap:16px;">
            <div style="width:48px;height:48px;background:#dcfce7;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;">📖</div>
            <div>
                <div style="font-size:12px;color:#6b7280;">Sedang Meminjam</div>
                <div style="font-size:22px;font-weight:700;color:#16a34a;">{{ $activeNow }}</div>
            </div>
        </div>
    </div>
    <div class="section-card" style="margin:0;">
        <div class="section-body" style="padding:20px;display:flex;align-items:center;gap:16px;">
            <div style="width:48px;height:48px;background:#fef3c7;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;">🆕</div>
            <div>
                <div style="font-size:12px;color:#6b7280;">User Baru Bulan Ini</div>
                <div style="font-size:22px;font-weight:700;color:#d97706;">{{ $newUsers }}</div>
            </div>
        </div>
    </div>
</div>

<div class="section-card">
    <div class="section-head">
        <h3>📋 Daftar Pengguna</h3>
    </div>
    <div class="section-body" style="padding:0;overflow-x:auto;">
        <table class="data-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:14px 20px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Pengguna</th>
                    <th style="padding:14px 20px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Email</th>
                    <th style="padding:14px 20px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;border-bottom:2px solid #e5e7eb;background:#f9fafb;">No. Telepon</th>
                    <th style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Total Pinjam</th>
                    <th style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Status</th>
                    <th style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Bergabung</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr class="table-row" onclick="openDetail({{ $u->id }})" style="cursor:pointer;transition:background 0.15s;">
                    <td style="padding:14px 20px;border-bottom:1px solid #f3f4f6;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            @if($u->avatar)
                                <img src="{{ asset('storage/' . $u->avatar) }}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid #e5e7eb;">
                            @else
                                <div style="width:38px;height:38px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:700;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                            @endif
                            <span style="font-size:13.5px;font-weight:600;color:#111827;">{{ $u->name }}</span>
                        </div>
                    </td>
                    <td style="padding:14px 20px;font-size:13px;color:#6b7280;border-bottom:1px solid #f3f4f6;">{{ $u->email }}</td>
                    <td style="padding:14px 20px;font-size:13px;color:#6b7280;border-bottom:1px solid #f3f4f6;">{{ $u->no_telp ?? '-' }}</td>
                    <td style="padding:14px 20px;text-align:center;font-size:13px;font-weight:600;color:#111827;border-bottom:1px solid #f3f4f6;">{{ $u->peminjaman->count() }}</td>
                    <td style="padding:14px 20px;text-align:center;border-bottom:1px solid #f3f4f6;">
                        @if($u->peminjaman->whereIn('status', ['dipinjam', 'pending'])->count() > 0)
                            <span style="background:#fef3c7;color:#d97706;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;">Sedang Proses</span>
                        @else
                            <span style="background:#dcfce7;color:#16a34a;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;">Tidak Ada Pinjaman</span>
                        @endif
                    </td>
                    <td style="padding:14px 20px;text-align:center;font-size:12px;color:#9ca3af;border-bottom:1px solid #f3f4f6;">{{ $u->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:60px 20px;text-align:center;">
                        <div style="font-size:40px;margin-bottom:12px;">🔍</div>
                        <p style="font-size:14px;color:#6b7280;margin:0;">Belum ada data pengguna</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px 20px;display:flex;justify-content:center;">
        {{ $users->links('pagination::custom') }}
    </div>
</div>
@endsection

@section('detail-panel')
<div id="userDetailPanel">
    <div style="text-align:center;padding:40px 20px;">
        <div style="font-size:40px;margin-bottom:12px;">👤</div>
        <p style="font-size:13px;color:#6b7280;">Klik nama pengguna di tabel untuk melihat detail profilnya</p>
    </div>
</div>
@endsection

<script>
    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('mouseover', function() { this.style.background = '#f9fafb'; });
        row.addEventListener('mouseout', function() { this.style.background = ''; });
    });

    function openDetail(id) {
        const panel = document.getElementById('userDetailPanel');
        panel.innerHTML = '<div style="text-align:center;padding:40px;">Memuat data...</div>';

        fetch(`/petugas/user/detail/` + id)
            .then(response => response.json())
            .then(data => {
                let riwayatHtml = '';
                if (data.riwayat.length > 0) {
                    data.riwayat.forEach(r => {
                        let statusColor = '#16a34a';
                        let statusText = r.status;
                        if (statusText === 'Dipinjam') { statusColor = '#2563eb'; }
                        else if (statusText === 'Pending') { statusColor = '#d97706'; }
                        else if (statusText === 'Terlambat') { statusColor = '#dc2626'; }
                        
                        riwayatHtml += `<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f3f4f6;">
                            <span style="font-size:12px;color:#111827;font-weight:500;">`+r.judul+`</span>
                            <span style="font-size:11px;background:`+statusColor+`15;color:`+statusColor+`;padding:2px 8px;border-radius:10px;font-weight:600;">`+statusText+`</span>
                        </div>`;
                    });
                } else {
                    riwayatHtml = '<p style="font-size:12px;color:#9ca3af;text-align:center;padding:10px 0;">Belum ada riwayat</p>';
                }

                let avatarHtml = `<div style="width:64px;height:64px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:24px;font-weight:700;margin:0 auto 12px;">`+data.name.charAt(0).toUpperCase()+`</div>`;
                if(data.avatar) {
                    avatarHtml = `<img src="/storage/`+data.avatar+`" style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:3px solid #e5e7eb;margin:0 auto 12px;display:block;">`;
                }

                panel.innerHTML = `
                    <div style="text-align:center;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #e5e7eb;">
                        `+avatarHtml+`
                        <h4 style="margin:0 0 4px;font-size:16px;color:#111827;">`+data.name+`</h4>
                        <p style="margin:0;font-size:12px;color:#6b7280;">Bergabung sejak `+data.join_date+`</p>
                    </div>
                    <div style="margin-bottom:20px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:10px;"><span style="font-size:12px;color:#6b7280;">Email</span><span style="font-size:12px;font-weight:500;color:#111827;">`+data.email+`</span></div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:10px;"><span style="font-size:12px;color:#6b7280;">No. Telp</span><span style="font-size:12px;font-weight:500;color:#111827;">`+data.no_telp+`</span></div>
                        <div style="display:flex;justify-content:space-between;"><span style="font-size:12px;color:#6b7280;">Alamat</span><span style="font-size:12px;font-weight:500;color:#111827;text-align:right;max-width:150px;">`+data.alamat+`</span></div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px;">
                        <div style="background:#eff6ff;padding:12px;border-radius:8px;text-align:center;">
                            <div style="font-size:18px;font-weight:700;color:#2563eb;">`+data.total_pinjam+`</div>
                            <div style="font-size:11px;color:#6b7280;">Total Pinjam</div>
                        </div>
                        <div style="background:#dcfce7;padding:12px;border-radius:8px;text-align:center;">
                            <div style="font-size:18px;font-weight:700;color:#16a34a;">`+data.dipinjam_sekarang+`</div>
                            <div style="font-size:11px;color:#6b7280;">Dipinjam Sekarang</div>
                        </div>
                    </div>
                    <div>
                        <h5 style="font-size:13px;font-weight:600;color:#374151;margin:0 0 10px;">Riwayat Terakhir</h5>
                        `+riwayatHtml+`
                    </div>
                `;
            })
            .catch(() => {
                panel.innerHTML = '<div style="text-align:center;color:#dc2626;padding:40px;">Gagal memuat data.</div>';
            });
    }
</script>