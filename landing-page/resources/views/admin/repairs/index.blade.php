<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CRUD Perbaikan - FixLA Admin</title>
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
            <h4><i class="fas fa-tools"></i> Data Perbaikan Jalan</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fas fa-plus"></i> Tambah Perbaikan
            </button>

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr><th>No</th><th>Lokasi</th><th>Nama Petugas</th><th>Tanggal Mulai</th><th>Progress</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($repairs as $key => $repair)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $repair->report->lokasi ?? '-' }}</td>
                        <td>{{ $repair->nama_petugas }}</td>
                        <td>{{ $repair->tanggal_mulai->format('d/m/Y') }}</td>
                        <td>
                            <div class="progress"><div class="progress-bar bg-success" style="width: {{ $repair->progress }}%">{{ $repair->progress }}%</div></div>
                        </td>
                        <td>
                            @if($repair->status == 'dijadwalkan') <span class="badge bg-secondary">Dijadwalkan</span>
                            @elseif($repair->status == 'pengerjaan') <span class="badge bg-primary">Pengerjaan</span>
                            @else <span class="badge bg-success">Selesai</span> @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $repair->id }}">Edit</button>
                            <form action="{{ route('repairs.destroy', $repair->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $repair->id }}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('repairs.update', $repair->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-header"><h5>Edit Perbaikan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <div class="mb-2"><label>Nama Petugas</label><input type="text" name="nama_petugas" class="form-control" value="{{ $repair->nama_petugas }}" required></div>
                                        <div class="mb-2"><label>Tanggal Mulai</label><input type="date" name="tanggal_mulai" class="form-control" value="{{ $repair->tanggal_mulai->format('Y-m-d') }}" required></div>
                                        <div class="mb-2"><label>Progress (%)</label><input type="number" name="progress" class="form-control" min="0" max="100" value="{{ $repair->progress }}"></div>
                                        <div class="mb-2"><label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="dijadwalkan" {{ $repair->status=='dijadwalkan'?'selected':'' }}>Dijadwalkan</option>
                                                <option value="pengerjaan" {{ $repair->status=='pengerjaan'?'selected':'' }}>Pengerjaan</option>
                                                <option value="selesai" {{ $repair->status=='selesai'?'selected':'' }}>Selesai</option>
                                            </select>
                                        </div>
                                        <div class="mb-2"><label>Keterangan</label><textarea name="keterangan" rows="2" class="form-control">{{ $repair->keterangan }}</textarea></div>
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
            <form action="{{ route('repairs.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5>Tambah Perbaikan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-2"><label>Pilih Laporan</label>
                        <select name="report_id" class="form-control" required>
                            <option value="">-- Pilih Laporan --</option>
                            @foreach($reports as $report)
                                <option value="{{ $report->id }}">{{ $report->lokasi }} - {{ $report->nama_pelapor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2"><label>Nama Petugas</label><input type="text" name="nama_petugas" class="form-control" required></div>
                    <div class="mb-2"><label>Tanggal Mulai</label><input type="date" name="tanggal_mulai" class="form-control" required></div>
                    <div class="mb-2"><label>Progress (%)</label><input type="number" name="progress" class="form-control" min="0" max="100" value="0"></div>
                    <div class="mb-2"><label>Status</label>
                        <select name="status" class="form-control">
                            <option value="dijadwalkan">Dijadwalkan</option>
                            <option value="pengerjaan">Pengerjaan</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button></div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>