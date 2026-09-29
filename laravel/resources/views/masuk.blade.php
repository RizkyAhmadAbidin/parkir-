@extends('layouts.main')

@section('title', 'Kendaraan Masuk')

@section('container')

    <!-- Hero Header -->
    <div class="hero-banner">
        <div class="hero-decoration"></div>
        <div class="small-text">Form Transaksi</div>
        <h1>Input Kendaraan Masuk 🚗</h1>
        <p>Catat plat nomor kendaraan yang baru memasuki area parkir.</p>
    </div>

    <!-- Alert / Notifikasi -->
    @if(session()->has('success'))
        <div style="background: #dcfce7; color: #155724; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #16a34a; font-size: 14px;">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
        </div>
    @endif

    <div class="content-grid" style="grid-template-columns: 1fr 1fr;">
        
        <!-- Form Kendaraan Masuk -->
        <div class="card-white">
            <div class="card-title">
                <span><i class="fa-solid fa-pen-to-square" style="color: #4361ee; margin-right: 8px;"></i> Form Entry</span>
            </div>
            
           <!-- PERBAIKAN: Tambahkan action, method, dan @csrf -->
<form action="{{ route('kendaraan.masuk.store') }}" method="POST">
    @csrf

    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">NOMOR PLAT KENDARAAN</label>
        
        <!-- PERBAIKAN: Atribut name HARUS "no_plat" -->
        <input type="text" name="no_plat" class="form-control" placeholder="Contoh: B 1234 ABC" required autofocus style="width: 100%; padding: 12px 15px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 16px; font-weight: 600; text-transform: uppercase;">
    </div>

    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">JENIS KENDARAAN</label>
        
        <!-- PERBAIKAN: Atribut name "jenis" -->
        <select name="jenis" style="width: 100%; padding: 12px 15px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
            <option value="Mobil">Mobil</option>
            <option value="Motor">Motor</option>
        </select>
    </div>

    <button type="submit" style="width: 100%; background: #4361ee; color: white; border: none; padding: 14px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px;">
        <i class="fa-solid fa-floppy-disk" style="margin-right: 8px;"></i> SIMPAN KENDARAAN MASUK
    </button>
</form>
        </div>

        <!-- Info Slot & Status -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="card-white">
                <div class="card-title">Ketersediaan Slot</div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 36px; font-weight: 700; color: #16a34a;">{{ $slotTersisa ?? 0 }}</div>
                        <div style="font-size: 12px; color: #888;">Slot Parkir Tersisa</div>
                    </div>
                    <div class="stat-icon icon-green" style="width: 60px; height: 60px; font-size: 28px;">
                        <i class="fa-solid fa-square-parking"></i>
                    </div>
                </div>
            </div>

            <div class="card-white">
                <div class="card-title">Waktu Masuk Terakhir</div>
                <div style="font-size: 18px; font-weight: 600; color: #333;">
                    <i class="fa-regular fa-clock" style="color: #4361ee; margin-right: 8px;"></i> {{ $waktuMasukTerakhir ?? '-' }}
                </div>
            </div>
        </div>

        <div class="card-white" style="padding: 25px;">
    <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-ticket" style="color: #4361ee;"></i> SIMULASI TOMBOL GATE MASUK
    </h3>

    <!-- Form Pengeluaran Tiket Otomatis -->
    <form action="{{ route('kendaraan-masuk.store-otomatis') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 8px;">PILIH JENIS KENDARAAN</label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <label style="display: flex; align-items: center; justify-content: center; gap: 8px; border: 2px solid #e2e8f0; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px;">
                    <input type="radio" name="jenis_kendaraan" value="Motor" checked style="accent-color: #4361ee;">
                    <i class="fa-solid fa-motorcycle"></i> Motor
                </label>
                <label style="display: flex; align-items: center; justify-content: center; gap: 8px; border: 2px solid #e2e8f0; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px;">
                    <input type="radio" name="jenis_kendaraan" value="Mobil" style="accent-color: #4361ee;">
                    <i class="fa-solid fa-car"></i> Mobil
                </label>
            </div>
        </div>

        <!-- Tombol Besar Cetak Tiket Otomatis -->
        <button type="submit" style="width: 100%; background: #16a34a; color: white; border: none; padding: 16px; border-radius: 10px; font-weight: 700; font-size: 15px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3); transition: all 0.2s;">
            <i class="fa-solid fa-circle-down" style="font-size: 20px;"></i> TEKAN TOMBOL / AMBIL TIKET
        </button>
    </form>
</div>

    </div>

@endsection