<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\RekamPrestasi;
use App\Models\Siswa;

$siswa = Siswa::find(135);
if (!$siswa) {
    echo "Siswa not found\n";
    exit;
}

echo "Siswa: " . $siswa->nama . "\n";

$records = RekamPrestasi::where('id_siswa', 135)->with('jenisPrestasi')->get();

echo "Number of records: " . $records->count() . "\n";

$total = 0;
foreach ($records as $r) {
    echo "ID: " . $r->id . ", Tanggal: " . $r->tanggal_prestasi . ", Poin: " . $r->jenisPrestasi->poin_prestasi . "\n";
    $total += $r->jenisPrestasi->poin_prestasi;
}

echo "Total poin: " . $total . "\n";
