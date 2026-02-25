<a href="/admin/dashboard"
    class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-slate-700 ' }}">
    <i class="fas fa-tachometer-alt"></i>
    <span>Dashboard</span>
</a>

<!-- Absensi -->
<a href="/admin/absensi"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.admin.absensi') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-slate-700' }} rounded-lg transition-colors">
    <i class="fas fa-calendar-check"></i>
    <span>Absensi</span>
</a>

<!-- Kelola Siswa -->
<a href="/admin/siswa"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.siswa.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-slate-700' }} rounded-lg transition-colors">
    <i class="fas fa-users"></i>
    <span>Kelola Siswa</span>
</a>

<!-- Kelola Kelas -->
<a href="/admin/kelas"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.kelas.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-slate-700' }} rounded-lg transition-colors">
    <i class="fas fa-chalkboard"></i>
    <span>Kelola Kelas</span>
</a>

<!-- Kelola Guru -->
<a href="{{ route('admin.walikelas.index') }}"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.walikelas.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-slate-700' }} rounded-lg">
    <i class="fas fa-user-tie"></i>
    <span>Kelola Guru</span>
</a>

<!-- Kelola Jurusan -->
<a href="/admin/jurusan"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.jurusan.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-slate-700' }} rounded-lg transition-colors">
    <i class="fas fa-graduation-cap"></i>
    <span>Kelola Jurusan</span>
</a>

<!-- Kelola User -->
<a href="/admin/user"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.user.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-slate-700' }} rounded-lg transition-colors">
    <i class="fas fa-user-cog"></i>
    <span>Kelola User</span>
</a>

<!-- Pengaturan -->
<a href="/admin/settings"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.settings.*') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-slate-700' }} rounded-lg transition-colors">
    <i class="fas fa-cog"></i>
    <span>Pengaturan</span>
</a>
