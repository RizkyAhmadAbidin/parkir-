<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;

class RiwayatController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::latest()->get();
        return view('riwayat', compact('transaksi'));
    }
}