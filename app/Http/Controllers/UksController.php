<?php

namespace App\Http\Controllers;

use App\Http\Requests\IzinPulangRequest;
use App\Http\Requests\KunjunganRequest;
use App\Http\Requests\ObatRequest;
use App\Http\Requests\RekamMedisRequest;
use App\Http\Requests\StokObatRequest;
use App\Models\Absensi;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\KunjunganUks;
use App\Models\Obat;
use App\Models\RekamMedis;
use App\Models\RekamMedisObat;
use App\Models\Siswa;
use App\Models\StokObat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UksController extends Controller
{
    /* =======================
       DASHBOARD
    ======================= */
    public function dashboard(Request $request)
    {
        $perpage = $request->get('per_page', 10);
        $totalKunjungan = KunjunganUks::count();
        $kunjunganHariIni = KunjunganUks::where('tanggal', Carbon::today())->count();
        $kunjunganTerbaru = KunjunganUks::with(['siswa.kelas'])->latest()->paginate($perpage);
        $totalObat = Obat::count();
        $totalStok = StokObat::sum('jumlah');
        $stokMenipis = DB::table('tbl_obat as o')
            ->join('tbl_stok_obat as s', 'o.id_obat', '=', 's.id_obat')
            ->select('o.id_obat', DB::raw('SUM(s.jumlah) as total_stok'))
            ->groupBy('o.id_obat', 'o.nama_obat')
            ->having('total_stok', '<', 20)
            ->get()
            ->count();


        $stokHabis = DB::table('tbl_obat as o')
            ->leftJoin('tbl_stok_obat as s', 'o.id_obat', '=', 's.id_obat')
            ->select('o.id_obat', 'o.nama_obat', DB::raw('COALESCE(SUM(s.jumlah), 0) as total_stok'))
            ->groupBy('o.id_obat', 'o.nama_obat')
            ->having('total_stok', '=', 0)
            ->count();

        $daftarStokObat = DB::table('tbl_obat as o')
            ->leftJoin('tbl_stok_obat as s', 'o.id_obat', '=', 's.id_obat')
            ->select(
                'o.id_obat',
                'o.nama_obat',
                'o.kategori',
                DB::raw('COALESCE(SUM(s.jumlah), 0) as total_stok')
            )
            ->groupBy('o.id_obat', 'o.nama_obat', 'o.kategori')
            ->having('total_stok', '<', 20) // < 20 bisa diganti sesuai ambang stok menipis
            ->get();

        return view('uks.dashboard', compact(
            'kunjunganTerbaru',
            'totalKunjungan',
            'kunjunganHariIni',
            'totalObat',
            'totalStok',
            'stokHabis',
            'stokMenipis',
            'daftarStokObat',
            'perpage'
        ));
    }

    /* =======================
       MANAJEMEN OBAT
    ======================= */
    public function obatIndex(Request $request)
    {
        $query = Obat::with('stokObat');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'LIKE', "%{$search}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });
        }

        if ($kategori = $request->get('kategori')) {
            $query->where('kategori', $kategori);
        }
        $perpage = $request->get('per_page', 10);
        $obat = $query->paginate($perpage)->appends($request->query());

        $totalObat = Obat::count();
        $stokMenipis = DB::table('tbl_obat as o')
            ->join('tbl_stok_obat as s', 'o.id_obat', '=', 's.id_obat')
            ->select('o.id_obat', DB::raw('SUM(s.jumlah) as total_stok'))
            ->groupBy('o.id_obat', 'o.nama_obat')
            ->having('total_stok', '<', 20)
            ->get()
            ->count();

        $stokHabis = DB::table('tbl_obat as o')
            ->leftJoin('tbl_stok_obat as s', 'o.id_obat', '=', 's.id_obat')
            ->select('o.id_obat', 'o.nama_obat', DB::raw('COALESCE(SUM(s.jumlah), 0) as total_stok'))
            ->groupBy('o.id_obat', 'o.nama_obat')
            ->having('total_stok', '=', 0)
            ->count();

        // $perpage = $request->get('per_page', 10);
        $obat = Obat::with('stokObat')->paginate($perpage);

        // dd($obat);



        $totalKategori = Obat::distinct('kategori')->count('kategori');

        return view('uks.obat.index', compact('obat', 'perpage', 'stokHabis', 'totalObat', 'totalKategori', 'stokMenipis'));
    }

    public function obatCreate()
    {
        return view('uks.obat.create');
    }

    public function obatStore(ObatRequest $request)
    {
        Obat::create($request->validated());

        return redirect()->route('uks.obat.index')->with('success', 'Obat berhasil ditambahkan');
    }

    public function obatEdit($id)
    {
        $obat = Obat::findOrFail($id);

        return view('uks.obat.edit', compact('obat'));
    }

    public function obatUpdate(ObatRequest $request, $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->update($request->validated());

        return redirect()->route('uks.obat.index')->with('success', 'Obat berhasil diperbarui');
    }

    public function obatDestroy($id)
    {
        Obat::findOrFail($id)->delete();

        return redirect()->route('uks.obat.index')->with('success', 'Obat berhasil dihapus');
    }

    /* =======================
       STOK OBAT
    ======================= */
    public function stokIndex(Request $request)
    {
        $perpage = $request->get('per_page', 10);
        $stok = StokObat::with('obat')->paginate($perpage);

        return view('uks.stok.index', compact('stok', 'perpage'));
    }

    public function stokCreate()
    {
        $obat = Obat::all();

        return view('uks.stok.create', compact('obat'));
    }

    public function stokStore(StokObatRequest $request)
    {
        StokObat::create($request->validated());

        return redirect()->route('uks.stok.index')->with('success', 'Stok obat berhasil ditambahkan');
    }

    public function stokEdit($id)
    {
        $stok = StokObat::with('obat')->findOrFail($id);
        $obat = Obat::all();

        return view('uks.stok.edit', compact('stok', 'obat'));
    }

    public function stokUpdate(StokObatRequest $request, $id)
    {
        $stok = StokObat::findOrFail($id);
        $stok->update($request->validated());

        return redirect()->route('uks.stok.index')->with('success', 'Stok obat berhasil diperbarui');
    }

    public function stokDestroy($id)
    {
        StokObat::findOrFail($id)->delete();

        return redirect()->route('uks.stok.index')->with('success', 'Stok obat berhasil dihapus');
    }

    /* =======================
       REKAM MEDIS
    ======================= */
    public function rekamMedisIndex(Request $request)
    {
        $q = $request->get('q');
        $rekamMedisQuery = RekamMedis::with('siswa.kelas.jurusan');

        if ($q) {
            $rekamMedisQuery->whereHas('siswa', function ($sub) use ($q) {
                $sub->where('nama', 'LIKE', "%{$q}%")
                    ->orWhere('nisn', 'LIKE', "%{$q}%");
            });
            // also prepare siswa search results for the search dropdown
            $siswaResults = Siswa::with('kelas.jurusan')
                ->where('nama', 'LIKE', "%{$q}%")
                ->orWhere('nisn', 'LIKE', "%{$q}%")
                ->limit(10)
                ->get();
        }
        $perpage = $request->get('per_page', 10);
        $rekamMedis = $rekamMedisQuery->paginate($perpage)->appends($request->only('q'));

        // ensure variable exists for the view
        if (!isset($siswaResults)) {
            $siswaResults = collect();
        }

        return view('uks.rekam-medis.index', compact('rekamMedis', 'q', 'perpage', 'siswaResults'));
    }

    public function rekamMedisCreate(Request $request)
    {
        $siswa = Siswa::with('kelas.jurusan')->get();
        $selectedSiswa = $request->has('siswa') ? Siswa::find($request->siswa) : null;
        $obat = Obat::all();

        // Generate obat list with stock for Select2
        $obatList = Obat::with('stokObat')->get()->map(function ($obat) {
            return [
                'id'       => $obat->id_obat,
                'nama'     => $obat->nama_obat,
                'kategori' => $obat->kategori,
                'stok'     => $obat->stokObat->sum('jumlah') ?? 0,
                'text'     => $obat->nama_obat . ' (' . $obat->kategori . ') - Stok: ' . ($obat->stokObat->sum('jumlah') ?? 0),
            ];
        })->toArray();

        return view('uks.rekam-medis.create', compact(
            'siswa',
            'selectedSiswa',
            'obatList',
            'obat'
        ));
    }


    public function rekamMedisStore(RekamMedisRequest $request)
    {
        // dd($request->obat_diberikan);
        // exit;

        $data = $request->validated();

        // Extract obat_diberikan structure: obat_diberikan[id_obat][], [jumlah][], [aturan_pakai][]
        $obatIds     = $request->input('obat_diberikan.id_obat', []);
        $obatJumlah  = $request->input('obat_diberikan.jumlah', []);
        $obatAturan  = $request->input('obat_diberikan.aturan_pakai', []);

        // Build obat list
        $obatList = [];
        foreach ($obatIds as $key => $obatId) {
            if (!empty($obatId) && isset($obatJumlah[$key])) {
                $obatList[] = [
                    'id_obat'      => (int) $obatId,
                    'jumlah'       => (int) $obatJumlah[$key],

                ];
            }
        }

        // Base data untuk Rekam Medis
        $data = $request->only(['id_siswa', 'tanggal', 'keluhan', 'diagnosis']);

        try {
            DB::beginTransaction();

            // Create Rekam Medis
            $rekam = RekamMedis::create(array_merge($data, [
                'obat_diberikan' => !empty($obatList) ? json_encode($obatList) : null
            ]));

            // Jika ada obat diberikan → detail + pengurangan stok
            if (!empty($obatList)) {
                foreach ($obatList as $ob) {

                    // Insert detail pemakaian
                    RekamMedisObat::create([
                        'id_rekam_medis' => $rekam->id_rekam_medis,
                        'id_obat'        => $ob['id_obat'],
                        'jumlah'         => $ob['jumlah'],

                    ]);

                    // Kurangi stok via FIFO
                    $this->reduceStock(
                        (int) $ob['id_obat'],
                        (int) $ob['jumlah']
                    );
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan rekam medis: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan rekam medis');
        }

        return redirect()
            ->route('uks.rekam-medis.index')
            ->with('success', 'Rekam medis berhasil ditambahkan');
    }






    public function rekamMedisDestroy($id)
    {
        RekamMedis::findOrFail($id)->delete();

        return redirect()->route('uks.rekam-medis.index')->with('success', 'Rekam medis berhasil dihapus');
    }

    /**
     * Show all rekam medis for a given siswa
     */
    public function rekamMedisBySiswa($id_siswa)
    {
        $siswa = Siswa::with('kelas.jurusan')->findOrFail($id_siswa);

        // load rekam medis along with detailed obat records and obat master data
        $rekamMedis = RekamMedis::with(['obat.obat'])
            ->where('id_siswa', $id_siswa)
            ->orderByDesc('tanggal')
            ->get();

        return view('uks.rekam-medis.show', compact('siswa', 'rekamMedis'));
    }

    /* =======================
       KUNJUNGAN UKS
    ======================= */
    public function kunjunganIndex(Request $request)
    {
        $perpage = $request->get('per_page', 10);
        $kunjungan = KunjunganUks::with('siswa.kelas.jurusan', 'petugasUks', 'rekamMedis')->paginate($perpage);

        return view('uks.kunjungan.index', compact('kunjungan', 'perpage'));
    }

    public function kunjunganCreate()
    {
        $siswa = Siswa::with('kelas.jurusan')->get();

        return view('uks.kunjungan.create', compact('siswa'));
    }

    public function kunjunganStore(KunjunganRequest $request)
    {
        $kunjungan = KunjunganUks::create([
            'id_siswa' => $request->id_siswa,
            'id_petugas_uks' => Auth::id(),
            'tanggal' => Carbon::today(),
            'waktu' => Carbon::now()->format('H:i:s'),
            'jenis_kunjungan' => $request->jenis_kunjungan,
            'keterangan' => $request->keterangan,
        ]);

        if (in_array($request->jenis_kunjungan, ['sakit', 'cedera'])) {
            $rmData = [
                'id_siswa' => $request->id_siswa,
                'id_kunjungan' => $kunjungan->id_kunjungan,
                'tanggal' => Carbon::today(),
                'keluhan' => $request->keluhan,
                'diagnosis' => $request->diagnosis,
                'tindakan' => $request->tindakan,
                'catatan' => $request->catatan,
            ];

            $obatList = $request->obat_diberikan ?? null;
            if ($obatList) {
                $rmData['obat_diberikan'] = json_encode($obatList);
            }

            // Wrap RekamMedis creation + detail inserts + stock reduction in a transaction
            try {
                DB::beginTransaction();

                $rekam = RekamMedis::create($rmData);

                if ($obatList && is_array($obatList)) {
                    foreach ($obatList as $ob) {
                        if (empty($ob['id_obat']) || empty($ob['jumlah'])) {
                            continue;
                        }
                        \App\Models\RekamMedisObat::create([
                            'id_rekam_medis' => $rekam->id_rekam_medis,
                            'id_obat' => $ob['id_obat'],
                            'jumlah' => $ob['jumlah'],
                        ]);
                        $this->reduceStock((int) $ob['id_obat'], (int) $ob['jumlah']);
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Gagal menyimpan rekam medis pada kunjungan: ' . $e->getMessage());

                return back()->with('error', 'Terjadi kesalahan saat menyimpan rekam medis');
            }
        }

        if ($request->jenis_kunjungan === 'izin_pulang') {
            return $this->handleIzinPulang($request->id_siswa);
        }

        return redirect()->route('uks.kunjungan.index')->with('success', 'Kunjungan berhasil dicatat');
    }

    /* =======================
       IZIN PULANG
    ======================= */
    public function izinPulang(Request $request)
    {
        $perpage = $request->get('per_page', 10);
        $siswa = Siswa::with('kelas.jurusan')->get();
        $izin = KunjunganUks::with(['siswa.kelas.jurusan'])
            ->where('jenis_kunjungan', 'izin_pulang')
            ->latest('tanggal')
            ->latest('waktu')
            ->paginate($perpage);

        return view('uks.izin-pulang.index', compact('siswa', 'izin', 'perpage'));
    }

    public function createIzinPulang(Request $request)
    {
        $daftarSiswa = Siswa::with('kelas.jurusan')->get();
        $selectedSiswa = $request->has('siswa') ? Siswa::find($request->siswa) : null;

        return view('uks.izin-pulang.create', compact('daftarSiswa', 'selectedSiswa'));
    }

    /**
     * Daftar Siswa untuk UKS
     */
    public function siswaIndex(Request $request)
    {
        $query = Siswa::with('kelas.jurusan');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nisn', 'LIKE', "%{$search}%")
                    ->orWhere('card_code', 'LIKE', "%{$search}%");
            });
        }

        if ($kelas = $request->get('kelas')) {
            $query->where('id_kelas', $kelas);
        }

        if ($jurusan = $request->get('jurusan')) {
            $query->whereHas('kelas', function ($q) use ($jurusan) {
                $q->where('id_jurusan', $jurusan);
            });
        }

        $siswa = $query->get();

        $kelas = Kelas::all();
        $jurusan = Jurusan::all();

        return view('uks.siswa.index', compact('siswa', 'kelas', 'jurusan'));
    }

    public function storeIzinPulang(IzinPulangRequest $request)
    {
        // Ambil absensi hari ini
        $absensi = Absensi::where('id_siswa', $request->id_siswa)
            ->where('tanggal', Carbon::today())
            ->first();

        // Validasi: apakah siswa sudah absen masuk
        if (!$absensi) {
            return redirect()->route('uks.izin-pulang')
                ->with('error', 'Siswa tidak melakukan presensi masuk');
        }

        // Validasi: apakah siswa sudah absen pulang
        if ($absensi->waktu_pulang) {
            return redirect()->route('uks.izin-pulang')
                ->with('error', 'Siswa sudah melakukan absensi pulang hari ini');
        }

        // Simpan izin pulang
        KunjunganUks::create([
            'id_siswa' => $request->id_siswa,
            'id_petugas_uks' => Auth::id(),
            'tanggal' => Carbon::today(),
            'waktu' => Carbon::now()->format('H:i:s'),
            'jenis_kunjungan' => 'izin_pulang',
            'keterangan' => $request->keterangan,
        ]);


        return $this->handleIzinPulang($request->id_siswa);
    }


    private function handleIzinPulang($idSiswa)
    {
        $absensi = Absensi::where('id_siswa', $idSiswa)
            ->where('tanggal', Carbon::today())
            ->first();

        if ($absensi) {
            $absensi->update([
                'status_pulang' => 'pulang_sakit',
                'waktu_pulang' => Carbon::now()->format('H:i:s'),
            ]);

            return redirect()->route('uks.izin-pulang')->with('success', 'Izin pulang berhasil diberikan');
        }

        return redirect()->route('uks.izin-pulang')->with('error', 'Data absensi tidak ditemukan');
    }

    /* =======================
       LAPORAN OBAT KELUAR
    ======================= */
    public function obatKeluarIndex()
    {
        $data = RekamMedis::with('siswa')->paginate(10);

        return view('uks.obat-keluar.index', compact('data'));
    }

    public function exportObatKeluar()
    {
        $data = $data = RekamMedis::with('siswa')->get();

        // Decode JSON per item
        $data = $data->map(function ($item) {
            $decoded = json_decode($item->obat_diberikan, true);

            $item->obat_decoded = is_array($decoded) ? $decoded : [];
            return $item;
        });

        return Excel::download(
            new \App\Exports\ObatKeluarExport($data),
            'laporan-obat-keluar.xlsx'
        );
    }





    /**
     * Reduce stock for a given obat id by quantity (FIFO across StokObat batches)
     */
    private function reduceStock(int $idObat, int $jumlah)
    {
        $stokBatch = StokObat::where('id_obat', $idObat)
            ->where('jumlah', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->get();

        $sisa = $jumlah;

        foreach ($stokBatch as $batch) {
            if ($sisa <= 0) break;

            if ($batch->jumlah >= $sisa) {
                $batch->update(['jumlah' => $batch->jumlah - $sisa]);
                $sisa = 0;
            } else {
                $sisa -= $batch->jumlah;
                $batch->update(['jumlah' => 0]);
            }
        }

        if ($sisa > 0) {
            Log::warning("Stok obat ID:$idObat tidak cukup. Kurang: $sisa");
        }
    }


    /* =======================
       EKSPOR DATA
    ======================= */
    public function exportKunjungan()
    {
        return Excel::download(new \App\Exports\KunjunganExport, 'data-kunjungan-uks.xlsx');
    }

    public function exportRekamMedis()
    {
        return Excel::download(new \App\Exports\RekamMedisExport, 'data-rekam-medis.xlsx');
    }

    public function exportIzinPulang()
    {
        return Excel::download(new \App\Exports\IzinPulangExport, 'data-izin-pulang.xlsx');
    }

    public function exportObat()
    {
        return Excel::download(new \App\Exports\ObatExport, 'data-obat.xlsx');
    }

    public function searchSiswa(Request $request)
    {
        $query = $request->get('q');

        // Ambil data siswa yang cocok dengan nama atau NISN
        $students = Siswa::with(['kelas.jurusan'])
            ->where('nama', 'like', "%{$query}%")
            ->orWhereRaw('CAST(nisn AS CHAR) LIKE ?', ["%{$query}%"])
            ->limit(10)
            ->get();

        // Format data untuk dropdown (biar ringan dan rapi)
        $formatted = $students->map(function ($s) {
            return [
                'id_siswa' => $s->id_siswa,
                'nama' => $s->nama,
                'nisn' => $s->nisn,
                'kelas' => $s->kelas->nama_kelas ?? '-',
                'jurusan' => $s->kelas->jurusan->nama_jurusan ?? '-',
            ];
        });

        return response()->json($formatted);
    }
    public function create()
    {
        $obat = Obat::all();
        return view('uks.rekam-medis.create', compact('obat'));
    }

    public function settings()
    {
        $user = auth()->user();
        return view('uks.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:tbl_user,username,' . auth()->id() . ',id_user',
            'password' => 'nullable|string|min:6',
        ]);

        $user = auth()->user();

        $userData = ['username' => $request->username];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $user->update($userData);

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui');
    }
}
