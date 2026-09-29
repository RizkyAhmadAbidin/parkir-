@extends('layouts.main')

@section('title', 'Kendaraan Keluar')

@section('container')
<div class="container-fluid" style="padding: 20px;">

    <!-- Banner Header -->
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); padding: 30px; border-radius: 16px; color: white; margin-bottom: 25px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
        <span style="font-size: 12px; font-weight: 700; letter-spacing: 1px; opacity: 0.8; text-transform: uppercase;">FORM TRANSAKSI</span>
        <h2 style="font-size: 26px; font-weight: 800; margin: 8px 0 4px 0; display: flex; align-items: center; gap: 10px;">
            Input Kendaraan Keluar 🏎️
        </h2>
        <p style="margin: 0; opacity: 0.85; font-size: 14px;">Proses pembayaran dan penyelesaian durasi parkir kendaraan.</p>
    </div>

    <!-- Alert Notifikasi Flash Message -->
    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Content Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

        <!-- Card 1: Form Cari Plat Nomor -->
        <div style="background: white; border-radius: 14px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
            <div style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-receipt" style="color: #ea580c;"></i> Cari & Bayar
            </div>

            <form action="{{ route('kendaraan.keluar.form') }}" method="GET">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">CARI NOMOR PLAT</label>
                    <input type="text"
                           name="no_plat"
                           class="form-control"
                           placeholder="Masukkan Plat Nomor (Contoh: B 1234 ABC)"
                           value="{{ request('no_plat') }}"
                           required
                           autofocus
                           style="width: 100%; padding: 12px 15px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none;">
                </div>

                <button type="submit" style="width: 100%; background: #ea580c; color: white; border: none; padding: 14px; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fa-solid fa-calculator"></i> HITUNG TARIF & KELUAR
                </button>
            </form>
        </div>

        <!-- Card 2: Ringkasan Sesi Parkir -->
        <div style="background: white; border-radius: 14px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
            <div style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 20px;">
                Ringkasan Sesi Parkir
            </div>

            <div style="margin-bottom: 20px;">
                <div style="font-size: 12px; color: #64748b; font-weight: 600;">Total Kendaraan Sedang Parkir</div>
                <div style="font-size: 32px; font-weight: 800; color: #2563eb; margin-top: 4px;">
                    {{ $kendaraanSedangParkir ?? 0 }}
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 15px 0;">

            <div>
                <div style="font-size: 12px; color: #64748b; font-weight: 600;">Keluar Terakhir</div>
                <div style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 4px;">
                    {{ $waktuKeluarTerakhir ?? '-' }}
                </div>
            </div>
        </div>

    </div>

</div>

<!-- MODAL POP-UP RINCIAN PEMBAYARAN (hanya satu) -->
@if(isset($transaksi) && $transaksi)
<div id="modalRincian" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); display: flex; align-items: center; justify-content: center; z-index: 9999; backdrop-filter: blur(4px);">
    <div style="background: white; border-radius: 16px; width: 100%; max-width: 450px; padding: 25px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);">

        <!-- Header Modal -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-receipt" style="color: #ea580c;"></i> Rincian Pembayaran
            </h3>
            <a href="{{ route('kendaraan.keluar.form') }}" style="color: #94a3b8; text-decoration: none; font-size: 22px; font-weight: bold; line-height: 1;">&times;</a>
        </div>

        <!-- Detail Rincian -->
        <div style="background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px;">
                <span style="color: #64748b;">Kode Tiket</span>
                <span style="font-weight: 700; color: #0f172a;">{{ $transaksi->kode_tiket ?? '-' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px;">
                <span style="color: #64748b;">Nomor Plat</span>
                <span style="font-weight: 800; color: #2563eb; font-size: 15px;">{{ $transaksi->no_plat }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px;">
                <span style="color: #64748b;">Jenis Kendaraan</span>
                <span style="font-weight: 600; color: #0f172a;">{{ $transaksi->jenis_kendaraan }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px;">
                <span style="color: #64748b;">Waktu Masuk</span>
                <span style="font-weight: 600; color: #0f172a;">{{ \Carbon\Carbon::parse($transaksi->waktu_masuk)->format('d M Y, H:i') }} WIB</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px;">
                <span style="color: #64748b;">Durasi Parkir</span>
                <span style="font-weight: 700; color: #d97706;">{{ $durasiJam ?? 1 }} Jam</span>
            </div>

            <hr style="border: none; border-top: 1px dashed #cbd5e1; margin: 12px 0;">

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 14px; font-weight: 700; color: #0f172a;">TOTAL BAYAR</span>
                <span style="font-size: 22px; font-weight: 800; color: #16a34a;">Rp {{ number_format($totalBiaya ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Form Submit Konfirmasi Bayar & Keluar -->
        <form action="{{ route('kendaraan.keluar.store', $transaksi->id) }}" method="POST">
            @csrf
            <input type="hidden" name="biaya" value="{{ $totalBiaya ?? 0 }}">

            <div style="display: flex; gap: 10px;">
                <a href="{{ route('kendaraan.keluar.form') }}" style="flex: 1; text-align: center; background: #f1f5f9; color: #475569; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">
                    Batal
                </a>
                <button type="submit" style="flex: 2; background: #16a34a; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);">
                    <i class="fa-solid fa-check-circle"></i> Bayar & Selesaikan
                </button>
            </div>
        </form>

    </div>
</div>
@endif
@endsection