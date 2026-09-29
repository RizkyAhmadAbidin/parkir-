<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class KendaraanKeluarController extends Controller
{
    public function index(Request $request)
    {
        $transaksi = null;
        $kendaraanParkir = Transaksi::where('status', 'Masuk')->count();
        $trxKeluarTerakhir = Transaksi::where('status', 'Keluar')->latest('waktu_keluar')->first();
        $waktuKeluarTerakhir = $trxKeluarTerakhir ? Carbon::parse($trxKeluarTerakhir->waktu_keluar)->format('H:i') . ' WIB' : '-';

        if ($request->has('no_plat')) {
            $transaksi = Transaksi::where('no_plat', strtoupper($request->no_plat))
                                  ->where('status', 'Masuk')
                                  ->first();
        }

        return view('keluar', compact('transaksi', 'kendaraanParkir', 'waktuKeluarTerakhir'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $biayaParkir = 5000;

        $transaksi->update([
            'status'       => 'Keluar',
            'waktu_keluar' => now(),
            'biaya'        => $biayaParkir,
        ]);

        return redirect()->route('kendaraan.keluar.form')->with('success', 'Kendaraan ' . $transaksi->no_plat . ' berhasil keluar! Biaya: Rp ' . number_format($biayaParkir, 0, ',', '.'));
    }
}