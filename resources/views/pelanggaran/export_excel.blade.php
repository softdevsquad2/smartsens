<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Jenis Pelanggaran</th>
            <th>Poin</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dataPelanggaran as $index => $rekam)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $rekam->tanggal_pelanggaran }}</td>
            <td>{{ $rekam->siswa->nis ?? 'N/A' }}</td>
            <td>{{ $rekam->siswa->nama_siswa ?? 'N/A' }}</td>
            <td>{{ $rekam->siswa->kelas->nama_kelas ?? 'N/A' }}</td>
            <td>{{ $rekam->pelanggaran->nama_pelanggaran ?? 'N/A' }}</td>
            <td>{{ $rekam->pelanggaran->poin_pelanggaran ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
