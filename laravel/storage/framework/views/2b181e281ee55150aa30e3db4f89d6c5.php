<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARK-iR | <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>
    
    <!-- Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles CSS -->
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7f6; display: flex; color: #333; }
        
        .sidebar { width: 250px; background-color: #4361ee; color: white; height: 100vh; position: fixed; display: flex; flex-direction: column; padding-top: 20px; }
        .sidebar-brand { display: flex; align-items: center; gap: 10px; padding: 0 20px 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px; }
        .sidebar-brand i { font-size: 24px; }
        .sidebar-brand-text { font-size: 20px; font-weight: 700; font-family: 'Montserrat', sans-serif; }
        .nav-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.6); padding: 10px 20px; font-weight: 600; }
        .nav-btn { color: rgba(255,255,255,0.8); text-decoration: none; display: flex; align-items: center; gap: 15px; padding: 12px 20px; transition: 0.3s; font-size: 14px; }
        .nav-btn i { font-size: 18px; width: 20px; text-align: center; }
        .nav-btn:hover, .nav-btn.active { background-color: rgba(255,255,255,0.1); color: white; border-left: 4px solid white; }
        .logout-form { margin-top: auto; padding: 20px; }
        .logout-btn { background: rgba(255,255,255,0.1); border: none; width: 100%; border-radius: 6px; cursor: pointer; justify-content: center; color: white; }

        .main-wrapper { margin-left: 250px; width: calc(100% - 250px); min-height: 100vh; }
        .topbar { background: white; height: 70px; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .topbar-left { display: flex; align-items: center; gap: 15px; font-size: 18px; font-weight: 600; color: #555; }
        .content { padding: 30px; }
        
        .hero-banner { background-color: #2b419c; border-radius: 16px; padding: 40px; color: white; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(43, 65, 156, 0.2); position: relative; overflow: hidden; }
        .hero-banner .small-text { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; font-weight: 600; color: rgba(255,255,255,0.8); }
        .hero-banner h1 { font-size: 32px; font-weight: 700; margin-bottom: 10px; }
        .hero-banner p { font-size: 14px; color: rgba(255,255,255,0.8); }
        .hero-decoration { position: absolute; right: -50px; top: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center; border: 1px solid #f0f0f0; }
        .stat-info .num { font-size: 28px; font-weight: 700; margin-bottom: 4px; }
        .stat-info .label { font-size: 12px; color: #888; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
        .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; }
        
        .icon-blue { background: #e0e7ff; color: #4f46e5; }
        .icon-green { background: #dcfce7; color: #16a34a; }
        .icon-orange { background: #ffedd5; color: #ea580c; }
        .icon-purple { background: #f3e8ff; color: #9333ea; }

        .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
        .card-white { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f0f0f0; }
        .card-title { font-size: 16px; font-weight: 700; color: #333; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 10px; font-size: 12px; color: #888; text-transform: uppercase; border-bottom: 1px solid #eee; }
        td { padding: 15px 10px; font-size: 14px; color: #444; border-bottom: 1px solid #f9f9f9; }
        .badge { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; }
        .badge-masuk { background: #dcfce7; color: #16a34a; }
        .badge-keluar { background: #fee2e2; color: #dc2626; }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="main-wrapper">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <i class="fa-solid fa-bars" style="cursor: pointer; color: #888;"></i>
                <span>Sistem Parkir Digital</span>
            </div>
            <div class="topbar-right">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 14px; font-weight: 600;">Admin User</span>
                    <img src="https://ui-avatars.com/api/?name=Admin&background=4361ee&color=fff" alt="Avatar" style="width: 35px; border-radius: 50%;">
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="content">
            <?php echo $__env->yieldContent('container'); ?>
        </main>
    </div>

</body>
</html><?php /**PATH C:\laragon\www\parkir-\laravel\resources\views/layouts/main.blade.php ENDPATH**/ ?>