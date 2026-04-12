<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CRUD Laporan - FixLA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
        <a href="{{ route('logout') }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin logout?')">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</nav>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-file-alt"></i> Data Laporan Jalan Rusak</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fas fa-plus"></i> Tambah Laporan
            </button>

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr><th>No</th><th>Nama Pelapor</th><th>Lokasi</th><th>Status</th><th>Foto</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($reports as $key => $report)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $report->nama_pelapor }}</td>
                        <td>{{ $report->lokasi }}</td>
                        <td>
                            @if($report->status == 'pending') <span class="badge bg-warning">Pending</span>
                            @elseif($report->status == 'diproses') <span class="badge bg-info">Diproses</span>
                            @else <span class="badge bg-success">Selesai</span> @endif
                        </td>
                        <td>@if($report->foto) <img src="{{ asset('uploads/reports/'.$report->foto) }}" width="50"> @else - @endif</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $report->id }}">Edit</button>
                            <form action="{{ route('reports.destroy', $report->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $report->id }}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="modal-header"><h5>Edit Laporan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <div class="mb-2"><label>Nama Pelapor</label><input type="text" name="nama_pelapor" class="form-control" value="{{ $report->nama_pelapor }}" required></div>
                                        <div class="mb-2"><label>Lokasi</label><input type="text" name="lokasi" class="form-control" value="{{ $report->lokasi }}" required></div>
                                        <div class="mb-2"><label>Deskripsi</label><textarea name="deskripsi" rows="2" class="form-control" required>{{ $report->deskripsi }}</textarea></div>
                                        <div class="mb-2"><label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="pending" {{ $report->status=='pending'?'selected':'' }}>Pending</option>
                                                <option value="diproses" {{ $report->status=='diproses'?'selected':'' }}>Diproses</option>
                                                <option value="selesai" {{ $report->status=='selesai'?'selected':'' }}>Selesai</option>
                                            </select>
                                        </div>
                                        <div class="mb-2"><label>Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
                                    </div>
                                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"><h5>Tambah Laporan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-2"><label>Nama Pelapor</label><input type="text" name="nama_pelapor" class="form-control" required></div>
                    <div class="mb-2"><label>Lokasi</label><input type="text" name="lokasi" class="form-control" required></div>
                    <div class="mb-2"><label>Deskripsi</label><textarea name="deskripsi" rows="2" class="form-control" required></textarea></div>
                    <div class="mb-2"><label>Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button></div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>