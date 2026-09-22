<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARK-iR | Riwayat Transaksi</title>
    <!-- Menggunakan CSS Eksternal yang sama -->
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
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
                <a href="<?php echo e(route('dashboard')); ?>" class="nav-btn">DASHBOARD</a>
                <a href="<?php echo e(route('kendaraan.masuk.form')); ?>" class="nav-btn">KEND MASUK</a>
                <a href="<?php echo e(route('kendaraan.keluar.form')); ?>" class="nav-btn">KEND KELUAR</a>
                <a href="<?php echo e(route('riwayat')); ?>" class="nav-btn" style="background: #cfcfcf;">RIWAYAT</a>
            </nav>
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
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
                        <?php $__empty_1 = true; $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;"><?php echo e($index + 1); ?></td>
                                <td style="padding: 10px; font-weight: 600;"><?php echo e($trx->no_plat); ?></td>
                                <td style="padding: 10px;"><?php echo e($trx->jenis_kendaraan ?? '-'); ?></td>
                                <td style="padding: 10px;"><?php echo e($trx->lokasi_parkir ?? '-'); ?></td>
                                <td style="padding: 10px;">
                                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: <?php echo e($trx->status == 'Masuk' ? '#d4edda' : '#f8d7da'); ?>; color: <?php echo e($trx->status == 'Masuk' ? '#155724' : '#721c24'); ?>;">
                                        <?php echo e($trx->status); ?>

                                    </span>
                                </td>
                                <td style="padding: 10px;"><?php echo e($trx->waktu_masuk ? $trx->waktu_masuk->format('d/m/Y H:i') : '-'); ?></td>
                                <td style="padding: 10px;"><?php echo e($trx->waktu_keluar ? $trx->waktu_keluar->format('d/m/Y H:i') : '-'); ?></td>
                                <td style="padding: 10px;"><?php echo e($trx->biaya ? 'Rp ' . number_format($trx->biaya, 0, ',', '.') : '-'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 30px; color: #777;">Belum ada riwayat transaksi parkir.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>

    </div>
</body>
</html><?php /**PATH C:\laragon\www\parkir-\laravel\resources\views/riwayat.blade.php ENDPATH**/ ?>