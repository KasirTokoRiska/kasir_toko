<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Support\Facades\DB; // Menggunakan Illuminate\Support\Facades\DB untuk DB::raw()

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::count(); // Menggunakan count() langsung untuk menghitung jumlah user
        $pelanggan = Pelanggan::count(); // Menggunakan count() langsung untuk menghitung jumlah pelanggan
        $kategori = Kategori::count(); // Menggunakan count() langsung untuk menghitung jumlah kategori
        $produk = Produk::count(); // Menggunakan count() langsung untuk menghitung jumlah produk

        $penjualan = Penjualan::select(
            DB::raw('SUM(total) as jumlah_total'),
            DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') as tgl")
        )
            ->where('status', 'selesai')
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->groupBy('tgl')
            ->get();

        $nama_bulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $label = 'Transaksi ' . $nama_bulan[date('n') - 1] . ' ' . date('Y'); // Menggunakan 'n' untuk mendapatkan bulan saat ini

        $labels = [];
        $data = [];

        foreach ($penjualan as $row) {
            $labels[] = substr($row->tgl, 0, 2);
            $data[] = $row->jumlah_total;
        }

        return view('welcome', [
            'user' => $user,
            'pelanggan' => $pelanggan,
            'kategori' => $kategori,
            'produk' => $produk,
            'cart' => [
                'label' => $label,
                'labels' => json_encode($labels),
                'data' => json_encode($data)
            ]
        ]);
    }
}
