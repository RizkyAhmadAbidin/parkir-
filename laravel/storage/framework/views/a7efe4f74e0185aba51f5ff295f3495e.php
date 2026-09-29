

<?php $__env->startSection('title', 'Riwayat Transaksi'); ?>

<?php $__env->startSection('container'); ?>

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
                <?php $__empty_1 = true; $__currentLoopData = $transaksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td style="font-weight: 600;"><?php echo e($trx->no_plat); ?></td>
                        <td>
                            <span class="badge <?php echo e($trx->status == 'Masuk' ? 'badge-masuk' : 'badge-keluar'); ?>">
                                <?php echo e($trx->status); ?>

                            </span>
                        </td>
                        <td><?php echo e($trx->waktu_masuk ? $trx->waktu_masuk->format('d/m/Y H:i') : '-'); ?> WIB</td>
                        <td><?php echo e($trx->waktu_keluar ? $trx->waktu_keluar->format('d/m/Y H:i') : '-'); ?> WIB</td>
                        <td><?php echo e($trx->durasi_parkir ?? '-'); ?> jam</td>
                        <td style="font-weight: 600;">
                            <?php echo e($trx->biaya ? 'Rp ' . number_format($trx->biaya, 0, ',', '.') : '-'); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #888; padding: 30px;">Belum ada riwayat transaksi parkir.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\parkir-\laravel\resources\views/riwayat.blade.php ENDPATH**/ ?>