<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARK-iR | Riwayat Transaksi</title>
    <!-- Menggunakan CSS Eksternal yang sama -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="dash" id="dash">

        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left"></div>
            <div class="topbar-right">
                <div class="brand-block">
                    <div class="brand-title">PARK-iR</div>
                    <div class="brand-sub">SISTEM PARKIR BERBASIS DIGITAL</div>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <nav class="nav-list">
                <a href="{{ route('dashboard') }}" class="nav-btn">DASHBOARD</a>
                <a href="{{ route('kendaraan.masuk.form') }}" class="nav-btn">KEND MASUK</a>
                <a href="{{ route('kendaraan.keluar.form') }}" class="nav-btn">KEND KELUAR</a>
                <a href="{{ route('riwayat') }}" class="nav-btn" style="background: #cfcfcf;">RIWAYAT</a>
            </nav>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-btn logout-btn" style="width: 100%;">LOGOUT</button>
            </form>
        </aside>

        <!-- Konten Utama Riwayat -->
        <main class="content">
            <h1 class="welcome" style="font-size: 20px; font-weight: 700; margin-bottom: 20px;">RIWAYAT TRANSAKSI PARKIR</h1>

            <!-- Kotak Tabel Riwayat -->
            <div style="border: 1.5px solid #b9b9bb; border-radius: 12px; padding: 20px; background: #fff; max-width: 1000px;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                    <thead>
                        <tr style="border-bottom: 2px solid #101114;">
                            <th style="padding: 10px;">NO.</th>
                            <th style="padding: 10px;">NO PLAT</th>
                            <th style="padding: 10px;">JENIS</th>
                            <th style="padding: 10px;">LOKASI</th>
                            <th style="padding: 10px;">STATUS</th>
                            <th style="padding: 10px;">WAKTU MASUK</th>
                            <th style="padding: 10px;">WAKTU KELUAR</th>
                            <th style="padding: 10px;">BIAYA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $index => $trx)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;">{{ $index + 1 }}</td>
                                <td style="padding: 10px; font-weight: 600;">{{ $trx->no_plat }}</td>
                                <td style="padding: 10px;">{{ $trx->jenis_kendaraan ?? '-' }}</td>
                                <td style="padding: 10px;">{{ $trx->lokasi_parkir ?? '-' }}</td>
                                <td style="padding: 10px;">
                                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: {{ $trx->status == 'Masuk' ? '#d4edda' : '#f8d7da' }}; color: {{ $trx->status == 'Masuk' ? '#155724' : '#721c24' }};">
                                        {{ $trx->status }}
                                    </span>
                                </td>
                                <td style="padding: 10px;">{{ $trx->waktu_masuk ? $trx->waktu_masuk->format('d/m/Y H:i') : '-' }}</td>
                                <td style="padding: 10px;">{{ $trx->waktu_keluar ? $trx->waktu_keluar->format('d/m/Y H:i') : '-' }}</td>
                                <td style="padding: 10px;">{{ $trx->biaya ? 'Rp ' . number_format($trx->biaya, 0, ',', '.') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 30px; color: #777;">Belum ada riwayat transaksi parkir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>

    </div>
</body>
</html>