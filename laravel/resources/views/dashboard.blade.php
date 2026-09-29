@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('container')

    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="hero-decoration"></div>
        <div class="small-text">Welcome Back</div>
        <h1>Hello, Admin 👋</h1>
        <p>Kelola sistem parkir Anda dengan efisien dari satu dashboard terpusat.</p>
    </div>

    <!-- Warning Occupancy -->
    @if(isset($occupancyRate) && $occupancyRate > 80)
        <div style="background: #fff3cd; color: #856404; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #ffc107; font-size: 14px;">
            <i class="fa-solid fa-triangle-exclamation" style="margin-right: 10px;"></i>
            <strong>Peringatan:</strong> Kapasitas parkir hampir penuh ({{ round($occupancyRate) }}% terisi).
        </div>
    @endif

    <!-- Statistik Cards -->
    <div class="stats-grid">
        
        <!-- Card 1 -->
        <div class="card-stat">
    <div style="font-size: 28px; font-weight: 800; color: #4361ee;">
        {{ $kendaraanSedangParkir }}
    </div>
    <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">
        KENDARAAN PARKIR
    </div>
    <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">
        M: {{ $mobilParkir }} | K: {{ $motorParkir }}
    </div>
</div>

        <!-- Card 2 -->
        <div class="card-stat">
    <div style="font-size: 28px; font-weight: 800; color: #16a34a;">
        {{ $slotTersisa }}
    </div>
    <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">
        SLOT TERSISA
    </div>
    <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">
        Dari total 100 slot
    </div>
</div>

        <!-- Card 3 -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="num" style="color: #ea580c;">{{ $totalOperator ?? 0 }}</div>
                <div class="label">Total Operator</div>
                <div style="font-size: 11px; color: #aaa; margin-top: 5px;">Termasuk {{ $totalAdmin ?? 0 }} Admin</div>
            </div>
            <div class="stat-icon icon-orange">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="num" style="color: #9333ea; font-size: 22px; margin-top: 5px;">Rp{{ number_format($pendapatanHarian, 0, ',', '.') }}</div>
                <div class="label">Pendapatan Harian</div>
                <div style="font-size: 11px; color: #aaa; margin-top: 5px;">
                    Trend: <span style="color: {{ $trendRevenue > 0 ? '#16a34a' : '#dc2626' }}">{{ $trendRevenue > 0 ? '+' : '' }}{{ round($trendRevenue) }}%</span>
                </div>
            </div>
            <div class="stat-icon icon-purple">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

    </div>

    <!-- Content Grid (Tabel & Aktivitas) -->
    <div class="content-grid">
        
        <!-- Tabel Transaksi -->
        <div class="card-white">
            <div class="card-title">
                Transaksi Terbaru
                <a href="{{ route('riwayat') }}" style="font-size: 12px; color: #4361ee; text-decoration: none; font-weight: 600;">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No Plat</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th>Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru as $index => $trx)
                        <tr>
                            <td style="font-weight: 600;">{{ $trx->no_plat }}</td>
                            <td>
                                <span class="badge {{ $trx->status == 'Masuk' ? 'badge-masuk' : 'badge-keluar' }}">
                                    {{ $trx->status }}
                                </span>
                            </td>
                            <td>{{ $trx->waktu_masuk ? $trx->waktu_masuk->format('H:i') : '-' }} WIB</td>
                            <td>{{ $trx->durasi_parkir ?? '-' }} jam</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #888;">Belum ada data transaksi hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Waktu Terakhir & Durasi -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="card-white">
                <div class="card-title">Aktivitas Terakhir</div>
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 11px; color: #888; font-weight: 600; text-transform: uppercase;">Masuk Terakhir</div>
                    <div style="font-size: 15px; font-weight: 500; color: #333;"><i class="fa-regular fa-clock" style="color: #16a34a; margin-right: 5px;"></i> {{ $waktuMasukTerakhir ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size: 11px; color: #888; font-weight: 600; text-transform: uppercase;">Keluar Terakhir</div>
                    <div style="font-size: 15px; font-weight: 500; color: #333;"><i class="fa-regular fa-clock" style="color: #dc2626; margin-right: 5px;"></i> {{ $waktuKeluarTerakhir ?? '-' }}</div>
                </div>
            </div>

            <div class="card-white" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); color: white;">
                <div class="card-title" style="color: white; opacity: 0.9;">Durasi Rata-rata</div>
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">
                    <i class="fa-solid fa-hourglass-half" style="font-size: 24px; opacity: 0.8;"></i> {{ $durasiRataRata ?? 0 }} <span style="font-size: 16px; font-weight: 500; opacity: 0.8;">jam</span>
                </div>
                <div style="font-size: 12px; opacity: 0.8;">Rata-rata waktu parkir kendaraan hari ini.</div>
            </div>
        </div>

    </div>

@endsection