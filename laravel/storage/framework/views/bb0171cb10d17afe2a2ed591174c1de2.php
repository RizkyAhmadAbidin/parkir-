<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARK-iR | Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;0,800;1,800;1,900&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARK-iR | Dashboard Admin</title>
    <!-- Memanggil CSS eksternal dari folder public/css/style.css -->
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
                <a href="<?php echo e(route('dashboard')); ?>" class="nav-btn" style="background: #cfcfcf;">DASHBOARD</a>
                <a href="<?php echo e(route('kendaraan.masuk.form')); ?>" class="nav-btn">KEND MASUK</a>
                <a href="<?php echo e(route('kendaraan.keluar.form')); ?>" class="nav-btn">KEND KELUAR</a>
                <a href="<?php echo e(route('riwayat')); ?>" class="nav-btn">RIWAYAT</a>
            </nav>
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-btn logout-btn" style="width: 100%;">LOGOUT</button>
            </form>
        </aside>

        <!-- Konten Dashboard Utama -->
        <main class="content">
            <h1 class="welcome" style="font-size: 20px; font-weight: 700; margin-bottom: 24px;">SELAMAT DATANG ADMIN!</h1>

            <!-- Statistik Ringkasan -->
            <div style="display: flex; gap: 40px; margin-bottom: 30px;">
                <div>
                    <h3 style="font-size: 14px; color: #555; margin-bottom: 8px;">KENDARAAN PARKIR</h3>
                    <div style="font-size: 15px; font-weight: 600;">
                        Masuk: <span style="color: #2f327d;"><?php echo e($kendaraanMasuk); ?></span> 
                        <span style="margin: 0 10px;">|</span> 
                        Keluar: <span style="color: #e3453b;"><?php echo e($kendaraanKeluar); ?></span>
                    </div>
                </div>
                <div>
                    <h3 style="font-size: 14px; color: #555; margin-bottom: 8px;">PENDAPATAN HARIAN</h3>
                    <div style="font-size: 18px; font-weight: 700; color: #101114;">
                        Rp <?php echo e(number_format($pendapatanHarian, 0, ',', '.')); ?>

                    </div>
                </div>
            </div>

            <hr style="border: none; border-top: 1.5px solid #c9c9c9; margin-bottom: 30px;">

            <!-- Waktu Terbaru -->
            <div style="display: flex; gap: 60px; margin-bottom: 30px; font-size: 14px; font-weight: 600;">
                <div>Masuk Terakhir : <span style="font-weight: 400;"><?php echo e($waktuMasukTerakhir); ?></span></div>
                <div>Keluar terakhir : <span style="font-weight: 400;"><?php echo e($waktuKeluarTerakhir); ?></span></div>
            </div>

            <!-- Tabel Transaksi Terbaru -->
            <div style="border: 1.5px solid #b9b9bb; border-radius: 12px; padding: 20px; max-width: 900px; background: #fff;">
                <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 15px;">TRANSAKSI TERBARU</h3>
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                    <thead>
                        <tr style="border-bottom: 2px solid #101114;">
                            <th style="padding: 8px; width: 10%;">NO.</th>
                            <th style="padding: 8px; width: 30%;">NO PLAT</th>
                            <th style="padding: 8px; width: 25%;">STATUS</th>
                            <th style="padding: 8px; width: 35%;">WAKTU</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $transaksiTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px 8px;"><?php echo e($index + 1); ?></td>
                                <td style="padding: 10px 8px; font-weight: 600;"><?php echo e($trx->no_plat); ?></td>
                                <td style="padding: 10px 8px;">
                                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: <?php echo e($trx->status == 'Masuk' ? '#d4edda' : '#f8d7da'); ?>; color: <?php echo e($trx->status == 'Masuk' ? '#155724' : '#721c24'); ?>;">
                                        <?php echo e($trx->status); ?>

                                    </span>
                                </td>
                                <td style="padding: 10px 8px;">
                                    <?php echo e($trx->waktu_masuk ? $trx->waktu_masuk->format('H:i') : '-'); ?> WIB
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px; color: #777;">Belum ada data transaksi hari ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>

    </div>
</body>
</html>
    <div class="dash" id="dash">

        <header class="topbar">
            <div class="topbar-left">
                <button class="icon-btn" id="navToggle" aria-label="Tampilkan / sembunyikan navbar">
                    <svg class="icon-profile" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M4 20c0-4.4 3.6-8 8-8s8 3.6 8 8"></path>
                    </svg>
                    <svg class="icon-hamburger" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                        stroke-linecap="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="topbar-right">
                <div class="brand-block">
                    <div class="brand-title">PARK-iR</div>
                    <div class="brand-sub">SISTEM PARKIR BERBASIS DIGITAL</div>
                </div>
                <div class="topbar-logo">
                    <img src="LOGO PARK-iR.png" alt="Logo PARK-iR"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-fallback">
                        <span class="P">P</span>
                        <span class="tag">PARK-IR</span>
                    </div>
                </div>
            </div>
        </header>

        <aside class="sidebar" id="sidebar">
            <nav class="nav-list">
                <a href="<?php echo e(route('dashboard')); ?>" class="nav-btn" style="text-decoration:none; display:block;">DASHBOARD</a>
                <a href="<?php echo e(route('kendaraan.masuk.form')); ?>" class="nav-btn" style="text-decoration:none; display:block;">KEND MASUK</a>
                <a href="<?php echo e(route('kendaraan.keluar.form')); ?>" class="nav-btn" style="text-decoration:none; display:block;">KEND KELUAR</a>
                <a href="#" class="nav-btn" style="text-decoration: none; display:block;">RIWAYAT</a>
            </nav>

            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-btn logout-btn" style="width: 100%">LOGOUT</button>
            </form>
            <button class="nav-btn logout-btn">LOGOUT</button>
        </aside>

        <main class="content">
            <h1 class="welcome">SELAMAT DATANG ADMIN!</h1>

            <div class="stats-area">
                <div class="stats-cols">

                    <div class="stat-block">
                        <div class="stat-label">KENDARAAN PARKIR</div>
                        <div class="stat-pair">
                            <div class="stat-item"><span class="stat-sub">Masuk</span><span class="stat-num"
                                    id="statMasuk"></span></div>
                            <span class="stat-sep">|</span>
                            <div class="stat-item"><span class="stat-sub">Keluar</span><span class="stat-num"
                                    id="statKeluar"></span></div>
                        </div>
                    </div>

                    <div class="stat-block">
                        <div class="stat-label">PENDAPATAN HARIAN</div>
                        <div class="stat-num-big" id="statPendapatan"></div>
                    </div>

                    <hr class="stat-line">

                    <div class="stat-block">
                        <div class="stat-label">TRANSAKSI TERBARU</div>
                    </div>

                    <div class="stat-block">
                        <div class="stat-label">WAKTU TERBARU</div>
                        <div class="stat-time">Masuk Terakhir&nbsp;|&nbsp;<span id="waktuMasukTerakhir"></span></div>
                        <div class="stat-time">Keluar terakhir&nbsp;|&nbsp;<span id="waktuKeluarTerakhir"></span></div>
                    </div>

                </div>

                <div class="table-card">
                    <div class="table-title">TRANSAKSI TERBARU</div>
                    <table>
                        <thead>
                            <tr>
                                <th>NO.</th>
                                <th>NO PLAT</th>
                                <th>STATUS</th>
                                <th>WAKTU</th>
                            </tr>
                        </thead>
                        <tbody id="transaksiBody">
                        </tbody>
                    </table>
                </div>

            </div>
        </main>

    </div>

    <script>
        const dash = document.getElementById('dash');
        const navToggle = document.getElementById('navToggle');

        navToggle.addEventListener('click', () => {
            dash.classList.toggle('collapsed');
            dash.style.setProperty('--side-w', dash.classList.contains('collapsed') ? '70px' : '186px');
        });
    </script>

</body>

</html><?php /**PATH C:\laragon\www\parkir-\laravel\resources\views/dashboard.blade.php ENDPATH**/ ?>