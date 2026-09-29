<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class KendaraanMasukController extends Controller {

public function index() {

    $slotTersisa = 100 - Transaksi::where('status', 'Masuk')->count();
    $trxMasukTerakhir = Transaksi::where('status', 'Masuk')->latest('waktu_masuk')->first();
    $waktuMasukTerakhir = $trxMasukTerakhir ? carbon::parse($trxMasukTerakhir->waktu_masuk)->format('H:i') . ' WIB': '-';

    return view('masuk', compact('slotTersisa', 'waktuMasukTerakhir'));
}

public function store(Request $request) {

    $request->validate([
        'no_plat' => 'required|string|max:15',
        'jenis'   => 'nullable|string',
    ]);

    $noPlat = strtoupper($request->no_plat);

    Transaksi::create([
        'no_plat'         => $noPlat,
        'jenis_kendaraan' => $request->jenis ?? 'Mobil',
        'status'          => 'Masuk',
        'waktu_masuk'     => now(),
    ]);

    return redirect()->back()->with('success', 'Data kendaraan masuk, (' . $noPlat .') berhasil disimpat!');

}
}