<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARK-iR | Kendaraan Masuk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;0,800;1,800;1,900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue: #5b6ee0;
            --blue-dark: #4c5fd6;
            --ink: #101114;
            --gray-btn: #dcdcdc;
            --gray-btn-border: #161616;
            --gray-line: #c9c9c9;
            --ok: #5f8f82;
            --danger: #e3453b;
            --side-w: 186px;
            --top-h: 121px;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0; padding: 0; height: 100%; width: 100%;
            overflow: hidden; font-family: 'Poppins', sans-serif;
            color: var(--ink);
        }

        .dash {
            display: grid;
            grid-template-columns: var(--side-w) 1fr;
            grid-template-rows: var(--top-h) 1fr;
            height: 100vh;
            width: 100vw;
        }

        .topbar {
            grid-column: 1 / 3;
            grid-row: 1;
            display: grid;
            grid-template-columns: var(--side-w) 1fr;
            background: var(--blue);
            background-image: radial-gradient(rgba(255, 255, 255, .05) 1px, transparent 1px);
            background-size: 3px 3px;
            border-bottom: 3px solid #111214;
        }

        .topbar-left {
            display: flex; align-items: center; justify-content: center;
            border-right: 2px solid #111214;
        }

        .topbar-right {
            position: relative; display: flex; align-items: center; justify-content: center; padding: 0 30px;
        }

        .brand-block { text-align: center; }
        .brand-title {
            font-family: 'Montserrat', sans-serif; font-weight: 900; font-style: italic;
            font-size: 44px; color: #fff; text-shadow: 0 2px 0 rgba(0, 0, 0, .12); line-height: 1;
        }
        .brand-sub { font-size: 15px; font-weight: 700; letter-spacing: 1px; color: #fff; margin-top: 4px; }

        .sidebar {
            grid-column: 1; grid-row: 2;
            background: var(--blue);
            background-image: radial-gradient(rgba(255, 255, 255, .05) 1px, transparent 1px);
            background-size: 3px 3px;
            display: flex; flex-direction: column; justify-content: space-between;
            padding: 20px 16px 24px; overflow: hidden;
        }

        .nav-list { display: flex; flex-direction: column; gap: 16px; }
        .nav-btn {
            width: 100%; background: var(--gray-btn); border: 1.5px solid var(--gray-btn-border);
            border-radius: 6px; padding: 14px 10px; font-family: 'Poppins', sans-serif;
            font-weight: 600; font-size: 14.5px; color: var(--ink); text-align: center;
            cursor: pointer; text-decoration: none; display: block;
        }
        .nav-btn:hover { background: #cfcfcf; }

        .content {
            grid-column: 2; grid-row: 2;
            background: #fff; padding: 26px 36px 30px; overflow: auto;
        }

        .welcome { font-size: 20px; font-weight: 700; color: #1c1c1e; margin: 0 0 24px; }

        /* Card Form */
        .card-form {
            border: 1.5px solid #b9b9bb;
            border-radius: 14px;
            padding: 24px 28px;
            max-width: 900px;
        }
        .card-title {
            font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px; margin-bottom: 4px; color: #1c1c1e;
        }
        .card-desc { font-size: 12px; color: #6b6b6f; margin-bottom: 24px; }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 40px;
        }

        .label-field { font-size: 13px; font-weight: 600; margin-bottom: 8px; display: block; }
        
        .plat-inputs { display: flex; gap: 10px; }
        .plat-inputs input {
            padding: 10px; border: 1.5px solid #b9b9bb; border-radius: 6px;
            font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600; text-align: center;
        }

        .vehicle-types { display: flex; gap: 15px; margin-top: 8px; }
        .vehicle-option {
            flex: 1; border: 1.5px solid #b9b9bb; border-radius: 8px; padding: 12px;
            text-align: center; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; font-weight: 600;
        }
        .vehicle-option input { accent-br color: var(--blue); }

        .select-lokasi {
            width: 100%; padding: 12px; border: 1.5px solid #b9b9bb; border-radius: 8px;
            font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; background: #fff;
        }

        .btn-group { display: flex; gap: 12px; margin-top: 30px; }
        .btn-batal {
            flex: 1; background: var(--danger); color: #fff; border: none; border-radius: 6px;
            padding: 12px; font-family: 'Poppins', sans-serif; font-weight: 600; text-align: center; text-decoration: none; cursor: pointer;
        }
        .btn-simpan {
            flex: 1; background: #2f327d; color: #fff; border: none; border-radius: 6px;
            padding: 12px; font-family: 'Poppins', sans-serif; font-weight: 600; cursor: pointer;
        }
        .alert-success {
            background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 600;
        }
    </style>
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

        <!-- Sidebar dengan Navigasi Aktif -->
        <aside class="sidebar" id="sidebar">
            <nav class="nav-list">
                <a href="{{ route('dashboard') }}" class="nav-btn">DASHBOARD</a>
                <a href="{{ route('kendaraan.masuk.form') }}" class="nav-btn" style="background: #cfcfcf;">KEND MASUK</a>
                <a href="{{ route('kendaraan.keluar.form') }}" class="nav-btn">KEND KELUAR</a>
                <a href="{{ route('riwayat') }}" class="nav-btn">RIWAYAT</a>
            </nav>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-btn logout-btn" style="width: 100%;">LOGOUT</button>
            </form>
        </aside>

        <!-- Konten Utama Form Kendaraan Masuk -->
        <main class="content">
            <h1 class="welcome">KENDARAAN MASUK</h1>

            <!-- Notifikasi Berhasil -->
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
            <div style="backgorund: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 13px;">
              <strong>Perhatian:</strong> Mohon isi semua kolom plat dengan benar!
            </div>
            @endif

            <div class="card-form">
                <div class="card-title">
                    <span>📄</span> INPUT KENDARAAN MASUK
                </div>
                <div class="card-desc">Silahkan isi data kendaraan yang akan masuk</div>

                <!-- Form yang terhubung ke Controller -->
                <form action="{{ route('kendaraan.masuk.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-grid">
                        <!-- Kolom Kiri -->
                        <div>
                            <div style="margin-bottom: 20px;">
                                <label class="label-field">Nomor Plat Kendaraan</label>
                                <div class="plat-inputs">
                                    <input type="text" name="plat_1" placeholder="ABC" style="width: 30%; text-transform: uppercase;" required>
                                    <input type="text" name="plat_2" placeholder="1234" style="width: 35%;" required>
                                    <input type="text" name="plat_3" placeholder="KSS" style="width: 35%; text-transform: uppercase;" required>
                                </div>
                            </div>

                            <div>
                                <label class="label-field">Jenis Kendaraan</label>
                                <div class="vehicle-types">
                                    <label class="vehicle-option" style="background: #f0f2f5;">
                                        <input type="radio" name="jenis_kendaraan" value="Mobil" checked> Mobil 🚗
                                    </label>
                                    <label class="vehicle-option" style="background: #f0f2f5;">
                                        <input type="radio" name="jenis_kendaraan" value="Motor"> Motor 🛵
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div>
                            <div style="margin-bottom: 20px;">
                                <label class="label-field">Lokasi Parkir (opsional)</label>
                                <select name="lokasi_parkir" class="select-lokasi">
                                    <option value="">Pilih Lokasi Parkir</option>
                                    <option value="Zona A - Lantai 1">Zona A - Lantai 1</option>
                                    <option value="Zona B - Lantai 2">Zona B - Lantai 2</option>
                                    <option value="Zona C - Basement">Zona C - Basement</option>
                                </select>
                            </div>

                            <div class="btn-group" style="margin-top: 48px;">
                                <a href="{{ route('dashboard') }}" class="btn-batal">Batal</a>
                                <button type="submit" class="btn-simpan">Simpan & Cetak</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </main>

    </div>
</body>
</html>