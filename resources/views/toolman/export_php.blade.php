<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat Peminjaman</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #444;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: center;
            padding: 6px;
        }

        td {
            padding: 6px;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>

    <h2>LAPORAN RIWAYAT PEMINJAMAN BARANG</h2>
    <p style="text-align:center; margin-top:-8px;">SMK NEGERI 2 TASIKMALAYA</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tujuan</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($peminjamans as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>

                    <td>{{ $p->siswa->nama ?? '-' }}</td>

                    <td class="text-center">
                        {{ $p->siswa->kelas->nama_kelas ?? '-' }}
                    </td>

                    <td>{{ $p->barang->nama_barang ?? '-' }}</td>

                    <td class="text-center">{{ $p->jumlah }}</td>

                    <td>{{ $p->tujuan ?? '-' }}</td>

                    <td class="text-center">
                        {{ $p->tanggal_pinjam ? date('d/m/Y', strtotime($p->tanggal_pinjam)) : '-' }}
                    </td>

                    <td class="text-center">
                        {{ $p->tanggal_kembali ? date('d/m/Y', strtotime($p->tanggal_kembali)) : '-' }}
                    </td>

                    <td class="text-center">
                        {{ ucfirst($p->status) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i') }}
    </div>

</body>

</html>
