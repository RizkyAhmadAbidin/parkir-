<!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-square-parking"></i>
            <span class="sidebar-brand-text">PARK-iR</span>
        </div>
        
        <div class="nav-label">Main Menu</div>
        <a href="<?php echo e(route('dashboard')); ?>" class="nav-btn active">
            <i class="fa-solid fa-grid-2"></i> Dashboard
        </a>
        <a href="<?php echo e(route('kendaraan.masuk.form')); ?>" class="nav-btn">
            <i class="fa-solid fa-arrow-right-to-bracket"></i> Kend. Masuk
        </a>
        <a href="<?php echo e(route('kendaraan.keluar.form')); ?>" class="nav-btn">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Kend. Keluar
        </a>
        
        <div class="nav-label" style="margin-top: 15px;">Data</div>
        <a href="<?php echo e(route('riwayat')); ?>" class="nav-btn">
            <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
        </a>

         <div class="nav-label" style="margin-top: 15px;">Manajemen</div>
        <a href="<?php echo e(route('user.index')); ?>" class="nav-btn">
            <i class="fa-solid fa-clock-rotate-left"></i> User
        </a>

        <form action="<?php echo e(route('logout')); ?>" method="POST" class="logout-form">
            <?php echo csrf_field(); ?>
            <button type="submit" class="nav-btn logout-btn">
                <i class="fa-solid fa-sign-out-alt"></i> LOGOUT
            </button>
        </form>
    </aside>
<?php /**PATH C:\laragon\www\parkir-\laravel\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>