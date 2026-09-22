<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARK-iR | Kendaraan Keluar</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
                <a href="{{ route('dashboard') }}" class="nav-btn">DASHBOARD</a>
                <a href="{{ route('kendaraan.masuk.form') }}" class="nav-btn">KEND MASUK</a>
                <a href="{{ route('kendaraan.keluar.form') }}" class="nav-btn" style="background: #cfcfcf;">KEND KELUAR</a>
                <a href="{{ route('riwayat') }}" class="nav-btn">RIWAYAT</a>
            </nav>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-btn logout-btn" style="width: 100%;">LOGOUT</button>
            </form>
        </aside>

        <!-- Konten Utama -->
        <main class="content">
            <h1 class="welcome" style="font-size: 20px; font-weight: 700; margin-bottom: 5px;">KENDARAAN KELUAR</h1>
            <div style="font-size: 13px; font-weight: 700; color: #2f327d; margin-bottom: 4px;">INPUT DATA KENDARAAN KELUAR</div>
            <div style="font-size: 12px; color: #6b6b6f; margin-bottom: 20px;">SILAHKAN CARI MANUAL ATAU SCAN BARCODE TICKET</div>

            <!-- Notifikasi Sukses / Error -->
            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; font-weight: 600;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; font-weight: 600;">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tombol Pilihan (Scan / Cari Manual) -->
            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <button type="button" style="flex: 1; background: #6b9ac4; color: #fff; border: none; padding: 14px; border-radius: 8px; font-weight: 600; cursor: pointer;">SCAN TIKET / BARCODE</button>
                <button type="button" onclick="toggleFormCari()" style="flex: 1; background: #6b9ac4; color: #fff; border: none; padding: 14px; border-radius: 8px; font-weight: 600; cursor: pointer;">CARI MANUAL</button>
            </div>

            <!-- Form Input Cari Plat (Disembunyikan dulu sebelum tombol Cari Manual diklik) -->
            <div id="formCariBox" style="display: {{ request('plat_1') ? 'block' : 'none' }}; border: 1.5px solid #b9b9bb; border-radius: 10px; padding: 16px; margin-bottom: 20px; background: #f9f9f9; max-width: 600px;">
                <form action="{{ route('kendaraan.keluar.form') }}" method="GET">
                    <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 8px;">Nomor Plat Kendaraan:</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="text" name="plat_1" placeholder="B" value="{{ request('plat_1') }}" style="width: 20%; padding: 8px; border: 1.5px solid #ccc; border-radius: 6px; text-align: center; text-transform: uppercase;" required>
                        <input type="text" name="plat_2" placeholder="1234" value="{{ request('plat_2') }}" style="width: 35%; padding: 8px; border: 1.5px solid #ccc; border-radius: 6px; text-align: center;" required>
                        <input type="text" name="plat_3" placeholder="KSS" value="{{ request('plat_3') }}" style="width: 35%; padding: 8px; border: 1.5px solid #ccc; border-radius: 6px; text-align: center; text-transform: uppercase;" required>
                        <button type="submit" style="background: #2f327d; color: #fff; border: none; padding: 9px 16px; border-radius: 6px; font-weight: 600; cursor: pointer;">Cari</button>
                    </div>
                </form>
            </div>

            <!-- Kotak Hasil Pencarian / Data Kendaraan -->
            <div style="border: 1.5px dashed #b9b9bb; border-radius: 10px; padding: 24px; text-align: center; max-width: 600px; margin-bottom: 25px; background: #fff;">
                @if(isset($transaksi) && $transaksi)
                    <div style="text-align: left; font-size: 14px;">
                        <p><strong>No. Plat:</strong> <span style="font-size: 18px; color: #2f327d;">{{ $transaksi->no_plat }}</span></p>
                        <p><strong>Jenis Kendaraan:</strong> {{ $transaksi->jenis_kendaraan ?? '-' }}</p>
                        <p><strong>Lokasi Parkir:</strong> {{ $transaksi->lokasi_parkir ?? '-' }}</p>
                        <p><strong>Waktu Masuk:</strong> {{ $transaksi->waktu_masuk ? $transaksi->waktu_masuk->format('d M Y - H:i') : '-' }} WIB</p>
                    </div>
                @else
                    <span style="color: #6b6b6f; font-size: 13px;">Belum ada data kendaraan. Silahkan scan tiket atau cari manual di atas.</span>
                @endif
            </div>

            <!-- Tombol Done / Proses Keluar -->
            @if(isset($transaksi) && $transaksi)
                <form action="{{ route('kendaraan.keluar.store', $transaksi->id) }}" method="POST">
                    @csrf
                    <button type="submit" style="background: linear-gradient(135deg, #8993f7, #6872e5); color: #fff; border: none; border-radius: 8px; padding: 12px 40px; font-weight: 700; font-size: 15px; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        ✓ DONE
                    </button>
                </form>
            @else
                <button type="button" disabled style="background: #dcdcdc; color: #888; border: none; border-radius: 8px; padding: 12px 40px; font-weight: 700; font-size: 15px; cursor: not-allowed;">
                    ✓ DONE
                </button>
            @endif
        </main>
    </div>

    <!-- Script kecil untuk tombol Cari Manual -->
    <script>
        function toggleFormCari() {
            const box = document.getElementById('formCariBox');
            if (box.style.display === 'none') {
                box.style.display = 'block';
            } else {
                box.style.display = 'none';
            }
        }
    </script>
</body>
</html>