<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
       $hariIni = Carbon::today();

       $kendaraanSedangParkir = Transaksi::where('status', 'masuk')->count();

       $mobilParkir = Transaksi::where('status', 'masuk')->where('jenis_kendaraan', 'Mobil')->count();
       $motorParkir = Transaksi::where('status', 'masuk')->where('jenis_kendaraan', 'Mobil')->count();

       $kendaraanMasuk  = Transaksi::whereDate('waktu_masuk', $hariIni)->count();
       $kendaraanKeluar = Transaksi::whereDate('waktu_keluar', $hariIni)->where('status', 'Keluar')->count();

       $totalSlot = 100;
       $slotTersisa = max(0, $totalSlot - $kendaraanSedangParkir);
       $occupancyRate = ($kendaraanSedangParkir / $totalSlot) * 100;

       $pendapatanHarian = Transaksi::whereDate('waktu_keluar', $hariIni)->sum('biaya');

       $trxMasukTerakhir = Transaksi::where('status', 'Masuk')->latest('waktu_masuk')->first();
       $trxKeluarTerakhir = Transaksi::where('status', 'Keluar')->latest('waktu_keluar')->first();

       $waktuMasukTerakhir = $trxMasukTerakhir ? Carbon::parse($trxMasukTerakhir->waktu_masuk)->format('H:i') . ' WIB' : '-';
       $waktuKeluarTerakhir = $trxKeluarTerakhir ? Carbon::parse($trxKeluarTerakhir->waktu_keluar)->format('H:i') . ' WIB' : '-';

       $transaksiTerbaru = Transaksi::latest('created_at')->limit(5)->get();

       

       $totalOperator = User::where('role', '!=', 'super_admin')->count();
       $totalAdmin = User::where('role', 'admin')->count();

       $durasiRataRata = Transaksi::whereDAte('waktu_keluar', $hariIni)
                        ->where('status', 'keluar')
                        ->whereNotNull('waktu_masuk')
                        ->whereNotNull('waktu_keluar')
                        ->get()
                        ->map(function($trx) {
                            $masuk = Carbon::parse($trx->waktu_masuk);
                            $keluar = Carbon::parse($trx->waktu_keluar);
                            return $masuk->diffInHours($keluar);
                        })
                        ->avg() ?? 0;

        $hariKemarin = Carbon::yesterday();
        $pendapatanKemarin = Transaksi::whereDate('waktu_keluar', $hariKemarin)->sum('biaya') ?? 0;

        $trendRevenue = 0;
        if ($pendapatanKemarin > 0) {
            $trendRevenue = (($pendapatanHarian - $pendapatanKemarin) /$pendapatanKemarin / $pendapatanKemarin) * 100;
        } elseif ($pendapatanHarian > 0 ) {
            $trendRevenue = 100;
        }

       return view('dashboard', compact(
        'kendaraanMasuk',
        'kendaraanKeluar',
        'pendapatanHarian',
        'waktuMasukTerakhir',
        'waktuKeluarTerakhir',
        'transaksiTerbaru',
        'slotTersisa',
        'totalSlot',
        'kendaraanSedangParkir',
        'mobilParkir',
        'motorParkir',
        'occupancyRate',
        'totalOperator',
        'totalAdmin',
        'durasiRataRata',
        'trendRevenue'
       ));
    }

    public function halamanMasuk() {
        
        return view('masuk');
    }

    public function halamanKeluar(Request $request)
{
    $transaksi = null;
    $durasiJam = 0;
    $totalBiaya = 0;

    // 1. Hitung variabel Ringkasan Sesi Parkir yang dipanggil view
    $kendaraanSedangParkir = Transaksi::where('status', 'masuk')->count();

    $trxKeluarTerakhir = Transaksi::where('status', 'keluar')->latest('waktu_keluar')->first();
    $waktuKeluarTerakhir = $trxKeluarTerakhir ? \Carbon\Carbon::parse($trxKeluarTerakhir->waktu_keluar)->format('H:i') . ' WIB' : '-';

    // 2. Jika user melakukan pencarian nomor plat
    if ($request->has('no_plat') && $request->no_plat != '') {
        $transaksi = Transaksi::where('no_plat', 'like', '%' . trim($request->no_plat) . '%')
                              ->where('status', 'masuk')
                              ->latest('waktu_masuk')
                              ->first();

        if ($transaksi) {
            $waktuMasuk = \Carbon\Carbon::parse($transaksi->waktu_masuk);
            $waktuKeluar = \Carbon\Carbon::now();

            // Hitung durasi jam
            $durasiJam = ceil($waktuMasuk->diffInMinutes($waktuKeluar) / 60);
            if ($durasiJam < 1) {
                $durasiJam = 1;
            }

            // Tarif: Mobil = Rp 5.000/jam | Motor = Rp 2.000/jam
            $tarifPerJam = ($transaksi->jenis_kendaraan == 'Mobil') ? 5000 : 2000;
            $totalBiaya = $durasiJam * $tarifPerJam;
        } else {
            return redirect()->route('kendaraan.keluar.form')
                             ->with('error', 'Kendaraan dengan plat ' . $request->no_plat . ' tidak ditemukan atau sudah keluar!');
        }
    }

    // 3. Kirim semua variabel ke view kendaraan-keluar
    return view('keluar', compact(
        'transaksi', 
        'durasiJam', 
        'totalBiaya', 
        'kendaraanSedangParkir', 
        'waktuKeluarTerakhir'
    ));
}

    public function storeKeluar(Request $request, $id)
{
    $transaksi = Transaksi::findOrFail($id);

    $transaksi->update([
        'waktu_keluar' => \Carbon\Carbon::now(),
        'biaya'        => $request->input('biaya', 0),
        'status'       => 'keluar',
        'id_petugas'   => auth()->id(),
    ]);

    return redirect()->route('kendaraan.keluar.form')
                     ->with('success', 'Transaksi selesai! Kendaraan ' . $transaksi->no_plat . ' telah keluar.');
}


    public function storeMasuk(Request $request) {
    
    $request->validate([
        'no_plat' => 'required|string|max:15',
        'jenis'   => 'nullable|string',
    ]);

    $noPlatGabungan = strtoupper($request->no_plat);

    Transaksi::create([
        'no_plat'         => $noPlatGabungan,
        'jenis_kendaraan' => $request->jenis ?? 'Mobil',
        'status'          => 'Masuk',
        'waktu_masuk'     => now(),
    ]);

    return redirect()->back()->with('success', 'Data kendaraan (' . $noPlatGabungan . ') berhasil disimpan!');
}

    public function riwayat() {

    $transaksi = Transaksi::latest()->get();

    return view('riwayat', compact('transaksi'));
    }

    public function storeOtomatis(Request $request)
{
    // 1. Ambil jenis kendaraan
    $jenis = $request->input('jenis_kendaraan', 'Motor');

    // 2. Generator Plat Nomor Dummy Acak (Contoh: B 4921 KRP)
    $kodeDepan = ['B', 'D', 'F', 'L', 'N'][rand(0, 4)];
    $angkaAcak = rand(1000, 9999);
    $hurufBelakang = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));
    $platDummy = $kodeDepan . ' ' . $angkaAcak . ' ' . $hurufBelakang;

    // 3. Generate Kode Tiket Unik
    $kodeTiket = 'TRX-' . date('YmdHis') . '-' . rand(10, 99);

    // 4. Simpan Transaksi Masuk
    $transaksi = Transaksi::create([
        'kode_tiket'      => $kodeTiket,
        'no_plat'         => $platDummy,
        'jenis_kendaraan' => $jenis,
        'jam_masuk'       => now(),
        'status'          => 'masuk', // Masih terparkir
        'id_petugas'      => auth()->id(),
    ]);

    return redirect()->back()->with('success', 'Tiket Berhasil Dicetak! Nomor Tiket: ' . $kodeTiket . ' | Plat Dummy: ' . $platDummy);
}
}