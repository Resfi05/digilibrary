@extends('layouts.petugas')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
@if($pending->count() > 0)
<div class="section-card" style="border-left:4px solid #f59e0b;">
    <div class="section-head">
        <h3>⏳ Permintaan Peminjaman Baru ({{ $pending->count() }})</h3>
    </div>
    <div class="section-body" style="padding:0;">
        @foreach($pending as $p)
        <div style="display:flex;align-items:center;gap:14px;padding:12px 18px;border-bottom:1px solid #f3f4f6;">
            <div style="width:40px;height:40px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">📥</div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;"><strong>{{ $p->user->name }}</strong> meminta meminjam <strong>{{ $p->book->title }}</strong></div>
                <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ date('d/m/Y H:i', strtotime($p->created_at)) }}</div>
            </div>
            <a href="{{ route('petugas.peminjaman.validasi') }}" class="btn btn-primary btn-sm">Proses</a>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($dikembalikan->count() > 0)
<div class="section-card" style="border-left:4px solid #059669;">
    <div class="section-head">
        <h3>✅ Buku Dikembalikan ({{ $dikembalikan->count() }})</h3>
    </div>
    <div class="section-body" style="padding:0;">
        @foreach($dikembalikan as $p)
        <div style="display:flex;align-items:center;gap:14px;padding:12px 18px;border-bottom:1px solid #f3f4f6;">
            <div style="width:40px;height:40px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">📤</div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;"><strong>{{ $p->user->name }}</strong> mengembalikan <strong>{{ $p->book->title }}</strong></div>
                <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ $p->tanggal_dikembalikan ? date('d/m/Y H:i', strtotime($p->tanggal_dikembalikan)) : '-' }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($ditolak->count() > 0)
<div class="section-card" style="border-left:4px solid #ef4444;">
    <div class="section-head">
        <h3>❌ Peminjaman Ditolak ({{ $ditolak->count() }})</h3>
    </div>
    <div class="section-body" style="padding:0;">
        @foreach($ditolak as $p)
        <div style="display:flex;align-items:center;gap:14px;padding:12px 18px;border-bottom:1px solid #f3f4f6;">
            <div style="width:40px;height:40px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">✗</div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;">Peminjaman <strong>{{ $p->book->title }}</strong> oleh <strong>{{ $p->user->name }}</strong> ditolak</div>
                <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ date('d/m/Y H:i', strtotime($p->created_at)) }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($dendaBelum->count() > 0)
<div class="section-card" style="border-left:4px solid #f59e0b;">
    <div class="section-head">
        <h3>💰 Denda Belum Dibayar ({{ $dendaBelum->count() }})</h3>
    </div>
    <div class="section-body" style="padding:0;">
        @foreach($dendaBelum as $p)
        @php $denda = $p->hitungDenda(); @endphp
        <div style="display:flex;align-items:center;gap:14px;padding:12px 18px;border-bottom:1px solid #f3f4f6;">
            <div style="width:40px;height:40px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">💸</div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;"><strong>{{ $p->user->name }}</strong> — <strong>{{ $p->book->title }}</strong></div>
                <div style="font-size:12px;color:#dc2626;font-weight:600;margin-top:2px;">Denda: Rp {{ number_format($denda, 0, ',', '.') }}</div>
            </div>
            <a href="{{ route('petugas.denda.index', ['tab' => 'belum']) }}" class="btn btn-warning btn-sm">Lihat</a>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($pending->count() == 0 && $dikembalikan->count() == 0 && $ditolak->count() == 0 && $dendaBelum->count() == 0)
<div class="empty-state" style="padding:60px 20px;">
    <div class="es-icon">🔔</div>
    <h3>Tidak Ada Notifikasi</h3>
    <p>Semua aktivitas sudah diproses</p>
</div>
@endif
@endsection

@section('detail-panel')
<div class="detail-empty">
    <div class="de-icon">🔔</div>
    <p>Notifikasi terbaru tampil di halaman utama</p>
</div>
@endsection