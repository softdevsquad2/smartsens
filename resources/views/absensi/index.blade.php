<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi GPS - SmartSens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    <style>
        .nav-link {
            color: #ecf0f1 !important;
            padding: 12px 16px;
            border-radius: 6px;
            margin: 2px 0;
        }
        .nav-link:hover {
            background: #34495e;
            color: white !important;
        }
        .nav-link.active {
            background: #3498db;
            color: white !important;
        }
        .table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .card {
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Hamburger Button -->
    <button class="hamburger" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/admin/absensi">
                                <i class="fas fa-calendar-check"></i> Absensi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/siswa">
                                <i class="fas fa-users"></i> Kelola Siswa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/kelas">
                                <i class="fas fa-chalkboard"></i> Kelola Kelas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/jurusan">
                                <i class="fas fa-graduation-cap"></i> Kelola Jurusan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/user">
                                <i class="fas fa-user-cog"></i> Kelola User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/settings">
                                <i class="fas fa-cog"></i> Pengaturan
                            </a>
                        </li>
                    </ul>
                </div>
    </nav>

    <!-- Main content -->
    <main class="main-content" id="mainContent">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Data Absensi</h1>
                </div>

                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="date" class="form-control" id="filterTanggal" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterKelas">
                            <option value="">Semua Kelas</option>
                            <!-- Options akan diisi dari database -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary" onclick="filterAbsensi()">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>

                <!-- Tabel Absensi -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Pulang</th>
                                <th>Status Masuk</th>
                                <th>Status Pulang</th>
                                <th>Lokasi Masuk</th>
                                <th>Lokasi Pulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensi as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->siswa->nama }}</td>
                                <td>{{ $item->siswa->kelas->nama_kelas }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                <td>{{ $item->waktu_masuk ?? '-' }}</td>
                                <td>{{ $item->waktu_pulang ?? '-' }}</td>
                                <td>
                                    @if($item->status_masuk == 'hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($item->status_masuk == 'terlambat')
                                        <span class="badge bg-warning">Terlambat</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Hadir</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status_pulang == 'pulang')
                                        <span class="badge bg-success">Pulang</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Pulang</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->latitude_masuk && $item->longitude_masuk)
                                        <a href="https://maps.google.com/?q={{ $item->latitude_masuk }},{{ $item->longitude_masuk }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-map-marker-alt"></i> Lihat
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($item->latitude_pulang && $item->longitude_pulang)
                                        <a href="https://maps.google.com/?q={{ $item->latitude_pulang }},{{ $item->longitude_pulang }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-map-marker-alt"></i> Lihat
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data absensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $absensi->links() }}
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterAbsensi() {
            const tanggal = document.getElementById('filterTanggal').value;
            const kelas = document.getElementById('filterKelas').value;
            
            // Implementasi filter
            window.location.href = `/admin/absensi?tanggal=${tanggal}&kelas=${kelas}`;
        }
        
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
        
        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
        
        // Close sidebar on window resize if mobile
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    </script>
</body>
</html>
