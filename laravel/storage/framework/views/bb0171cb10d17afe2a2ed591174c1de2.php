

<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('container'); ?>

    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="hero-decoration"></div>
        <div class="small-text">Welcome Back</div>
        <h1>Hello, Admin 👋</h1>
        <p>Kelola sistem parkir Anda dengan efisien dari satu dashboard terpusat.</p>
    </div>

    <!-- Warning Occupancy -->
    <?php if(isset($occupancyRate) && $occupancyRate > 80): ?>
        <div style="background: #fff3cd; color: #856404; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #ffc107; font-size: 14px;">
            <i class="fa-solid fa-triangle-exclamation" style="margin-right: 10px;"></i>
            <strong>Peringatan:</strong> Kapasitas parkir hampir penuh (<?php echo e(round($occupancyRate)); ?>% terisi).
        </div>
    <?php endif; ?>

    <!-- Statistik Cards -->
    <div class="stats-grid">
        
        <!-- Card 1 -->
        <div class="card-stat">
    <div style="font-size: 28px; font-weight: 800; color: #4361ee;">
        <?php echo e($kendaraanSedangParkir); ?>

    </div>
    <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">
        KENDARAAN PARKIR
    </div>
    <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">
        M: <?php echo e($mobilParkir); ?> | K: <?php echo e($motorParkir); ?>

    </div>
</div>

        <!-- Card 2 -->
        <div class="card-stat">
    <div style="font-size: 28px; font-weight: 800; color: #16a34a;">
        <?php echo e($slotTersisa); ?>

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
                <div class="num" style="color: #ea580c;"><?php echo e($totalOperator ?? 0); ?></div>
                <div class="label">Total Operator</div>
                <div style="font-size: 11px; color: #aaa; margin-top: 5px;">Termasuk <?php echo e($totalAdmin ?? 0); ?> Admin</div>
            </div>
            <div class="stat-icon icon-orange">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="num" style="color: #9333ea; font-size: 22px; margin-top: 5px;">Rp<?php echo e(number_format($pendapatanHarian, 0, ',', '.')); ?></div>
                <div class="label">Pendapatan Harian</div>
                <div style="font-size: 11px; color: #aaa; margin-top: 5px;">
                    Trend: <span style="color: <?php echo e($trendRevenue > 0 ? '#16a34a' : '#dc2626'); ?>"><?php echo e($trendRevenue > 0 ? '+' : ''); ?><?php echo e(round($trendRevenue)); ?>%</span>
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
                <a href="<?php echo e(route('riwayat')); ?>" style="font-size: 12px; color: #4361ee; text-decoration: none; font-weight: 600;">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
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
                    <?php $__empty_1 = true; $__currentLoopData = $transaksiTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="font-weight: 600;"><?php echo e($trx->no_plat); ?></td>
                            <td>
                                <span class="badge <?php echo e($trx->status == 'Masuk' ? 'badge-masuk' : 'badge-keluar'); ?>">
                                    <?php echo e($trx->status); ?>

                                </span>
                            </td>
                            <td><?php echo e($trx->waktu_masuk ? $trx->waktu_masuk->format('H:i') : '-'); ?> WIB</td>
                            <td><?php echo e($trx->durasi_parkir ?? '-'); ?> jam</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #888;">Belum ada data transaksi hari ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Waktu Terakhir & Durasi -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="card-white">
                <div class="card-title">Aktivitas Terakhir</div>
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 11px; color: #888; font-weight: 600; text-transform: uppercase;">Masuk Terakhir</div>
                    <div style="font-size: 15px; font-weight: 500; color: #333;"><i class="fa-regular fa-clock" style="color: #16a34a; margin-right: 5px;"></i> <?php echo e($waktuMasukTerakhir ?? '-'); ?></div>
                </div>
                <div>
                    <div style="font-size: 11px; color: #888; font-weight: 600; text-transform: uppercase;">Keluar Terakhir</div>
                    <div style="font-size: 15px; font-weight: 500; color: #333;"><i class="fa-regular fa-clock" style="color: #dc2626; margin-right: 5px;"></i> <?php echo e($waktuKeluarTerakhir ?? '-'); ?></div>
                </div>
            </div>

            <div class="card-white" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); color: white;">
                <div class="card-title" style="color: white; opacity: 0.9;">Durasi Rata-rata</div>
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">
                    <i class="fa-solid fa-hourglass-half" style="font-size: 24px; opacity: 0.8;"></i> <?php echo e($durasiRataRata ?? 0); ?> <span style="font-size: 16px; font-weight: 500; opacity: 0.8;">jam</span>
                </div>
                <div style="font-size: 12px; opacity: 0.8;">Rata-rata waktu parkir kendaraan hari ini.</div>
            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\parkir-\laravel\resources\views/dashboard.blade.php ENDPATH**/ ?>