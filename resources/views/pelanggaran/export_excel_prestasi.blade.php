<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Jenis Prestasi</th>
            <th>Pembimbing</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dataPrestasi as $index => $rekam)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $rekam->tanggal_prestasi }}</td>
            <td>{{ $rekam->siswa->nisn ?? 'N/A' }}</td>
            <td>{{ $rekam->siswa->nama ?? 'N/A' }}</td>
            <td>{{ $rekam->siswa->kelas->nama_kelas ?? 'N/A' }}</td>
            <td>{{ $rekam->jenisPrestasi->nama_prestasi ?? 'N/A' }}</td>
            <td>{{ $rekam->pembimbing ?? 'N/A' }}</td>
            <td>{{ $rekam->keterangan ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
