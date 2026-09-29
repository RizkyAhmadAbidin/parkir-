@extends('layouts.main')

@section('title', 'Riwayat Transaksi')

@section('container')

    <!-- Hero Header -->
    <div class="hero-banner" style="background-color: #1e293b;">
        <div class="hero-decoration"></div>
        <div class="small-text">Laporan Data</div>
        <h1>Riwayat Parkir 📜</h1>
        <p>Arsip seluruh transaksi kendaraan masuk dan keluar.</p>
    </div>

    <!-- Tabel Riwayat Lengkap -->
    <div class="card-white">
        <div class="card-title">
            <span>Daftar Seluruh Transaksi</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No Plat</th>
                    <th>Status</th>
                    <th>Waktu Masuk</th>
                    <th>Waktu Keluar</th>
                    <th>Durasi</th>
                    <th>Biaya</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $index => $trx)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="font-weight: 600;">{{ $trx->no_plat }}</td>
                        <td>
                            <span class="badge {{ $trx->status == 'Masuk' ? 'badge-masuk' : 'badge-keluar' }}">
                                {{ $trx->status }}
                            </span>
                        </td>
                        <td>{{ $trx->waktu_masuk ? $trx->waktu_masuk->format('d/m/Y H:i') : '-' }} WIB</td>
                        <td>{{ $trx->waktu_keluar ? $trx->waktu_keluar->format('d/m/Y H:i') : '-' }} WIB</td>
                        <td>{{ $trx->durasi_parkir ?? '-' }} jam</td>
                        <td style="font-weight: 600;">
                            {{ $trx->biaya ? 'Rp ' . number_format($trx->biaya, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #888; padding: 30px;">Belum ada riwayat transaksi parkir.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection