<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
       $hariIni = Carbon::today();

       $kendaraanMasuk  = Transaksi::whereDate('waktu_masuk', $hariIni)->where('status', 'Masuk')->count();
       $kendaraanKeluar = Transaksi::whereDate('waktu_keluar', $hariIni)->where('status', 'Keluar')->count();

       $pendapatanHarian = Transaksi::whereDate('waktu_keluar', $hariIni)->sum('biaya');

       $trxMasukTerakhir = Transaksi::where('status', 'Masuk')->latest('waktu_masuk')->first();
       $trxKeluarTerakhir = Transaksi::where('status', 'Keluar')->latest('waktu_keluar')->first();

       $waktuMasukTerakhir = $trxMasukTerakhir ? Carbon::parse($trxMasukTerakhir->waktu_masuk)->format('H:i') . ' WIB' : '-';
       $waktuKeluarTerakhir = $trxKeluarTerakhir ? Carbon::parse($trxKeluarTerakhir->waktu_keluar)->format('H:i') . ' WIB' : '-';

       $transaksiTerbaru = Transaksi::latest('created_at')->limit(5)->get();

       return view('dashboard', compact(
        'kendaraanMasuk',
        'kendaraanKeluar',
        'pendapatanHarian',
        'waktuMasukTerakhir',
        'waktuKeluarTerakhir',
        'transaksiTerbaru'
       ));
    }

    public function halamanMasuk() {
        
        return view('masuk');
    }

    public function halamanKeluar(Request $request) {

        $transaksi = null;

        if ($request->has('plat_1') && $request->has('plat_2') && $request->has('plat_3')) {
            $noPlatCari = strtoupper($request->plat_1 . ' ' . $request->plat_2 . ' ' . $request->plat_3 );

            $transaksi = Transaksi::where('no_plat', $noPlatCari)
                                  ->where('status', 'Masuk')
                                  ->first();

            if (!$transaksi) {
                return redirect()->back()->with('error', 'Kendaran dengan plat nomor' . $noPlatCari . 'tidak ditemukan atau sudah keluar!');
            }
        }

        return view('keluar', compact('transaksi'));
    }

    public function storeKeluar(Request $request, $id) {
        
        $transaksi = Transaksi::findOrFail($id);

        $biayaParkir = 5000;

        $transaksi->update([
            'status' => 'Keluar',
            'waktu_keluar' => now(),
            'biaya' => $biayaParkir,
        ]);

        return redirect()->route('kendaraan.keluar.form')->with('success', 'Kendaraan'  . $transaksi->no_plat . ' berhasil keluar! Biaya Rp ' . number_format($biayaParkir, 0, ',','.'));
    }


    public function storeMasuk(Request $request) {
        
        $request->validate([
            'plat_1' => 'required|string|max:2',
            'plat_2' => 'required|string|max:4',
            'plat_3' => 'required|string|max:3',
            'jenis_kendaraan' => 'required|string',
            'lokasi_parkir' => 'nullable|string',
        ]);

        $noPlatGabungan = strtoupper($request->plat_1 . ' ' . $request->plat_2 . ' ' . $request->plat_3);

        Transaksi::create([
            'no_plat' => $noPlatGabungan,
            'jenis_kendaraan' => $request->jenis_lendaran,
            'lokasi_parkir' => $request->lokasi_parkir,
            'status'  => 'Masuk',
            'waktu_masuk' => now(),
        ]);

        return redirect()->back()->with('success', 'Data kendaraan masuk berhasil disimpan!');
    }

    public function riwayat() {

    $transaksis = Transaksi::latest()->get();

    return view('riwayat', compact('transaksis'));
    }
}