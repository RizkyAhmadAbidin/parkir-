

<?php $__env->startSection('title', 'Kendaraan Keluar'); ?>

<?php $__env->startSection('container'); ?>

    <!-- Hero Header -->
    <div class="hero-banner" style="background-color: #3a0ca3;">
        <div class="hero-decoration"></div>
        <div class="small-text">Form Transaksi</div>
        <h1>Input Kendaraan Keluar 🏎️</h1>
        <p>Proses pembayaran dan penyelesaian durasi parkir kendaraan.</p>
    </div>

    <!-- Alert / Notifikasi -->
    <?php if(session()->has('success')): ?>
        <div style="background: #dcfce7; color: #155724; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #16a34a; font-size: 14px;">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session()->has('error')): ?>
        <div style="background: #fee2e2; color: #dc2626; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #dc2626; font-size: 14px;">
            <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="content-grid" style="grid-template-columns: 1fr 1fr;">
        
        <!-- Form Kendaraan Keluar -->
        <div class="card-white">
            <div class="card-title">
                <span><i class="fa-solid fa-receipt" style="color: #ea580c; margin-right: 8px;"></i> Cari & Bayar</span>
            </div>
            
            <form action="<?php echo e(route('kendaraan.keluar.form')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">CARI NOMOR PLAT</label>
                    <input type="text" name="no_plat" class="form-control" placeholder="Masukkan Plat Nomor" required autofocus style="width: 100%; padding: 12px 15px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 16px; font-weight: 600; text-transform: uppercase;">
                </div>

                <button type="submit" style="width: 100%; background: #ea580c; color: white; border: none; padding: 14px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px;">
                    <i class="fa-solid fa-calculator" style="margin-right: 8px;"></i> HITUNG TARIF & KELUAR
                </button>
            </form>
        </div>

        <!-- Info Transaksi Aktif -->
        <div class="card-white">
            <div class="card-title">Ringkasan Sesi Parkir</div>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <div style="font-size: 12px; color: #888;">Total Kendaraan Sedang Parkir</div>
                    <div style="font-size: 28px; font-weight: 700; color: #3a0ca3;"><?php echo e($kendaraanParkir ?? 0); ?></div>
                </div>
                <hr style="border: none; border-top: 1px solid #eee;">
                <div>
                    <div style="font-size: 12px; color: #888;">Keluar Terakhir</div>
                    <div style="font-size: 15px; font-weight: 600; color: #333;"><?php echo e($waktuKeluarTerakhir ?? '-'); ?></div>
                </div>
            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\parkir-\laravel\resources\views/keluar.blade.php ENDPATH**/ ?>