<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Jenis Pelanggaran</th>
            <th>Poin Pelanggaran</th>
            <th>Jenis Prestasi</th>
            <th>Poin Prestasi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataPelanggaran as $index => $rekam)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $rekam->tanggal_pelanggaran }}</td>
                <td>{{ $rekam->siswa->nisn ?? 'N/A' }}</td>
                <td>{{ $rekam->siswa->nama ?? 'N/A' }}</td>
                <td>{{ $rekam->siswa->kelas->nama_kelas ?? 'N/A' }}</td>
                <td>{{ $rekam->pelanggaran->nama_pelanggaran ?? 'N/A' }}</td>
                <td>{{ $rekam->poin_diberikan ?? 'N/A' }}</td>
                <td>{{ $rekam->nama_prestasi ?? 'N/A' }}</td>
                <td>{{ $rekam->total_poin_prestasi ?? 0 }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
