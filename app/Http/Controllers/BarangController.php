<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    // ===============================
    //  TAMPILAN DAFTAR BARANG
    // ===============================
    public function index()
    {
        // session()->forget('cart');
        $barang = Barang::all();
        return view('pinjam.index', compact('barang'));
    }

    // ===============================
    //  TAMBAH KE KERANJANG
    // ===============================
    public function addToCart(Request $request)
    {
        $id = $request->id_barang;

        $barang = Barang::find($id);
        if (!$barang) {
            return back()->with('error', 'Barang tidak ditemukan');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['jumlah']++;
        } else {
            $cart[$id] = [
                "id_barang" => $barang->id_barang,
                "nama_barang" => $barang->nama_barang,
                "jumlah" => 1,
                "stok" => $barang->stok,
                "gambar" => $barang->gambar
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Barang ditambahkan ke keranjang');
    }

    // ===============================
    //  HALAMAN KERANJANG
    // ===============================
    public function cart()
    {
        session()->forget('peminjam_id');
        $cart = session('cart', []);
        return view('pinjam.cart', compact('cart'));
    }

    // ===============================
    //  HALAMAN CHECKOUT
    // ===============================

    public function pilih()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('pinjam.index')
                ->with('error', 'Keranjang kosong!');
        }
        return view('pinjam.pilih', compact('cart'));
    }
    public function checkout()
    {
        // session()->forget('peminjam_id');
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('pinjam.index')
                ->with('error', 'Keranjang kosong!');
        }

        // Harus scan QR dulu
        if (!session()->has('peminjam_id')) {
            return redirect()->route('pinjam.scan');
        }

        return view('pinjam.checkout', compact('cart'));
    }

    // ===============================
    //  PROSES CHECKOUT
    // ===============================
    public function processCheckout(Request $request)
    {
        $request->validate([
            'tujuan' => 'required|string|max:255',
        ]);

        $cart = session('cart', []);
        $id_user = session('peminjam_id');

        if (!$id_user) {
            return redirect()->route('pinjam.scan')
                ->with('error', 'Scan QR NISN siswa terlebih dahulu!');
        }

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong!');
        }

        foreach ($cart as $item) {
            // simpan ke database
            Peminjaman::create([
                'id_user' => $id_user,
                'id_barang' => $item['id_barang'],
                'jumlah' => $item['jumlah'],
                'tujuan' => $request->tujuan,
                'tanggal_pinjam' => now(),
                'status' => 'dipinjam',
            ]);

            // kurangi stok
            Barang::where('id_barang', $item['id_barang'])
                ->decrement('stok', $item['jumlah']);
        }

        // hapus session cart & peminjam
        session()->forget('cart');
        session()->forget('peminjam_id');

        return redirect()->route('pinjam.index')
            ->with('success', 'Peminjaman berhasil!');
    }

    // ===============================
    //  HAPUS ITEM DARI CART
    // ===============================
    public function removeItem($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Barang dihapus dari keranjang');
    }

    // ===============================
    //  HALAMAN SCAN QR
    // ===============================
    public function scanPage()
    {
        session()->forget('peminjam_id');
        return view('pinjam.scan');
    }
    public function scanPageKembali()
    {
        session()->forget('peminjam_id');
        return view('pinjam.scanKembali');
    }
    public function kembalikanPinjam()
    {
        return view("pinjam.pilihscan");
    }

    // ===============================
    //  PROSES SCAN QR
    // ===============================
    public function scanProcess(Request $request)
    {
        $json = $request->qr;

        // QR kamu berbentuk JSON seperti:
        // {"nis_siswa":"0076548998","id_kelas":"55"}
        $data = json_decode($json);

        if (!$data || !isset($data->nis_siswa)) {
            return back()->with('error', 'Format QR tidak valid!');
        }

        $nisn = $data->nis_siswa; // ← ambil NIS dari QR

        // JOIN tbl_siswa + tbl_user
        $siswa = DB::table('tbl_siswa')
            ->join('tbl_user', 'tbl_user.id_siswa', '=', 'tbl_siswa.id_siswa')
            ->where('tbl_siswa.nisn', $nisn)   // ← sesuai struktur database kamu
            ->select(
                'tbl_user.id_user',
                'tbl_siswa.nama'
            )
            ->first();
        // dd($siswa);
        if (!$siswa) {
            return back()->with('error', 'Siswa tidak ditemukan dalam database!');
        }

        // Simpan id_user ke session
        session(['peminjam_id' => $siswa->id_user]);

        return redirect()->route('pinjam.checkout')
            ->with('success', 'Siswa diverifikasi: ' . $siswa->nama);
    }
    public function scanProcessBack(Request $request)
    {
        $json = $request->qr;

        // QR kamu berbentuk JSON seperti:
        // {"nis_siswa":"0076548998","id_kelas":"55"}
        $data = json_decode($json);

        if (!$data || !isset($data->nis_siswa)) {
            return back()->with('error', 'Format QR tidak valid!');
        }

        $nisn = $data->nis_siswa; // ← ambil NIS dari QR

        // JOIN tbl_siswa + tbl_user
        $siswa = DB::table('tbl_siswa')
            ->join('tbl_user', 'tbl_user.id_siswa', '=', 'tbl_siswa.id_siswa')
            ->where('tbl_siswa.nisn', $nisn)   // ← sesuai struktur database kamu
            ->select(
                'tbl_user.id_user',
                'tbl_siswa.nama'
            )
            ->first();
        // dd($siswa);
        if (!$siswa) {
            return back()->with('error', 'Siswa tidak ditemukan dalam database!');
        }

        // Simpan id_user ke session
        session(['peminjam_id' => $siswa->id_user]);

        return redirect()->route('pinjam.kembali');
        // $this->kembalikan();
    }
    public function kembalikan()
    {
        $id_user = session('peminjam_id');
        // dd($id_user);
        if (!$id_user) {
            return redirect()->route('kembali.scan')
                ->with('error', 'Scan QR NISN siswa terlebih dahulu!');
        }
        $peminjaman = Peminjaman::with(['user', 'siswa', 'barang'])
            ->where('id_user', $id_user)
            ->where('status', 'dipinjam')
            ->get();

        $siswa = $peminjaman->first()->siswa ?? null;
        $user  = $peminjaman->first()->user ?? null;
        // dd($peminjaman);

        if ($peminjaman->isEmpty()) {
            return redirect()->route('kembali.scan')
                ->with('error', 'Tidak ada barang yang sedang dipinjam oleh siswa ini.');
        }

        return view('pinjam.kembali', compact('peminjaman', 'siswa', 'user'));
    }
    public function processKembalikan(Request $request)
    {
        // Validasi: harus pilih minimal 1 barang
        $request->validate([
            'barang' => 'required|array',
        ], [
            'barang.required' => 'Pilih minimal satu barang yang ingin dikembalikan.'
        ]);

        $id_user = session('peminjam_id');

        if (!$id_user) {
            return redirect()->route('pinjam.scan')
                ->with('error', 'Scan QR NISN siswa terlebih dahulu!');
        }

        // Ambil hanya peminjaman yang dipilih
        $selected = Peminjaman::whereIn('id_peminjaman', $request->barang)
            ->where('id_user', $id_user)
            ->where('status', 'dipinjam')
            ->get();

        if ($selected->isEmpty()) {
            // return back()->with('error', 'Barang yang dipilih tidak valid.');
            return redirect()->route('pinjam.kembali')
                ->with('error', 'Barang tidak valid.');
        }

        // Proses pengembalian
        foreach ($selected as $pinjam) {
            $pinjam->status = 'dikembalikan';
            $pinjam->tanggal_kembali = now();
            $pinjam->save();

            // Tambah stok kembali
            Barang::where('id_barang', $pinjam->id_barang)
                ->increment('stok', $pinjam->jumlah);
        }

        // Jika semua barang siswa sudah dikembalikan → hapus session peminjam_id
        $sisa = Peminjaman::where('id_user', $id_user)
            ->where('status', 'dipinjam')
            ->count();

        if ($sisa === 0) {
            session()->forget('peminjam_id');
        }

        return redirect()->route('pinjam.index')
            ->with('success', 'Barang terpilih berhasil dikembalikan!');
    }
    public function updateQty(Request $request)
    {
        $cart = session()->get('cart', []);

        $id = $request->id_barang;
        $action = $request->action;

        if (!isset($cart[$id])) {
            return back()->with('error', 'Barang tidak ditemukan.');
        }

        // Tambah
        if ($action === "plus") {
            $cart[$id]['jumlah'] += 1;
        }

        // Kurangi
        if ($action === "minus" && $cart[$id]['jumlah'] > 1) {
            $cart[$id]['jumlah'] -= 1;
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Jumlah diperbarui.');
    }
    public function scanCardPage()
    {
        return view('pinjam.scan-card');
    }
    public function scanCard()
    {
        return view('pinjam.card');
    }
}
