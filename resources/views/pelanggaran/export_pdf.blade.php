<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pelanggaran Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pelanggaran Siswa</h1>
        <p>SMK Negeri 1 Jakarta</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">NIS</th>
                <th>Nama Siswa</th>
                <th class="text-center">Kelas</th>
                <th>Jenis Pelanggaran</th>
                <th class="text-center">Poin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataPelanggaran as $index => $rekam)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $rekam->tanggal_pelanggaran }}</td>
                <td class="text-center">{{ $rekam->siswa->nis ?? 'N/A' }}</td>
                <td>{{ $rekam->siswa->nama_siswa ?? 'N/A' }}</td>
                <td class="text-center">{{ $rekam->siswa->kelas->nama_kelas ?? 'N/A' }}</td>
                <td>{{ $rekam->pelanggaran->nama_pelanggaran ?? 'N/A' }}</td>
                <td class="text-center">{{ $rekam->pelanggaran->poin_pelanggaran ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
