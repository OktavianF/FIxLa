<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - FixLA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard Admin</a>
        <div class="d-flex">
            <span class="text-white me-3">Halo, {{ $adminNama }}</span>
            <a href="{{ route('logout') }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin logout?')">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Laporan</h5>
                    <h2>{{ $totalLaporan }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5>Perbaikan Aktif</h5>
                    <h2>{{ $totalPerbaikan }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Laporan Pending</h5>
                    <h2>{{ $laporanPending }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Laporan Selesai</h5>
                    <h2>{{ $laporanSelesai }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Menu Cepat</div>
                <div class="card-body">
                    <a href="{{ route('reports.index') }}" class="btn btn-primary w-100 mb-2">Kelola Laporan</a>
                    <a href="{{ route('repairs.index') }}" class="btn btn-warning w-100">Kelola Perbaikan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>