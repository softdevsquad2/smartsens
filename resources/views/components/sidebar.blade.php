@section('sidebar')
    <a href="{{ route('uks.dashboard') }}"
        class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-green-700 {{ request()->routeIs('uks.dashboard') ? 'bg-green-700 text-white' : 'text-gray-200' }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('uks.rekam-medis.index') }}"
        class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-green-700 {{ request()->routeIs('uks.rekam-medis.*') ? 'bg-green-700 text-white' : 'text-gray-200' }}">
        <i class="fas fa-file-medical"></i>
        <span>Daftar Rekam Medis</span>
    </a>

    <a href="{{ route('uks.siswa.index') }}"
        class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-green-700 {{ request()->routeIs('uks.siswa.*') ? 'bg-green-700 text-white' : 'text-gray-200' }}">
        <i class="fas fa-users"></i>
        <span>Daftar Siswa</span>
    </a>

    <a href="{{ route('uks.obat.index') }}"
        class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-green-700 {{ request()->routeIs('uks.obat.index') ? 'bg-green-700 text-white' : 'text-gray-200' }}">
        <i class="fas fa-pills"></i>
        <span>Kelola Obat</span>
    </a>

    <a href="{{ route('uks.stok.index') }}"
        class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-green-700 {{ request()->routeIs('uks.stok.index') ? 'bg-green-700 text-white' : 'text-gray-200' }}">
        <i class="fas fa-boxes"></i>
        <span>Kelola Stok</span>
    </a>

    <a href="{{ route('uks.obat-keluar.index') }}"
        class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-green-700 {{ request()->routeIs('uks.obat-keluar.index') ? 'bg-green-700 text-white' : 'text-gray-200' }}">
        <i class="fas fa-notes-medical"></i>
        <span>Obat Keluar</span>
    </a>

    <a href="{{ route('uks.kunjungan.index') }}"
        class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-green-700 {{ request()->routeIs('uks.kunjungan.index') ? 'bg-green-700 text-white' : 'text-gray-200' }}">
        <i class="fas fa-user-friends"></i>
        <span>Kunjungan Siswa</span>
    </a>

    <a href="{{ route('uks.izin-pulang') }}"
        class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-green-700 {{ request()->routeIs('uks.izin-pulang') ? 'bg-green-700 text-white' : 'text-gray-200' }}">
        <i class="fas fa-door-open"></i>
        <span>Izin Pulang</span>
    </a>

    <a href="{{ route('uks.profile.settings', ['userId' => auth()->id()]) }}"
        class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-green-700 {{ request()->routeIs('uks.profile.settings') ? 'bg-green-700 text-white' : 'text-gray-200' }}">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>
@endsection
