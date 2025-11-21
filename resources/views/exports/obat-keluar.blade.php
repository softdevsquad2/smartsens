<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nama Siswa</th>
            <th>NISN</th>
            <th>Obat Diberikan</th>
            <th>Diagnosis</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $d)
            <tr>
                <td>{{ $d->tanggal }}</td>
                <td>{{ $d->siswa->nama }}</td>
                <td>{{ $d->siswa->nisn }}</td>

                <td>
                    @php
                        $obatData = $d->obat_decoded ?? [];
                    @endphp


                    @if (!empty($obatData))
                        @foreach ($obatData as $ob)
                            @php
                                $namaObat = \App\Models\Obat::where('id_obat', $ob['id_obat'])->value('nama_obat');
                            @endphp
                            {{ $namaObat ?? 'Tidak diketahui' }} ({{ $ob['jumlah'] }})
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    @else
                        -
                    @endif

                </td>

                <td>{{ $d->diagnosis }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
