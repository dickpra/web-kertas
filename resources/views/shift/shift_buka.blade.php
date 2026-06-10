<!DOCTYPE html>
<html lang="id">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Buka Shift Kerja</title>
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 500px;">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center fw-bold">MULAI SHIFT FORKLIFT</div>
            <div class="card-body">
                <form action="{{ url('/shift/mulai') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Nama Kepala Shift</label>
                        <input type="text" name="kepala_shift" class="form-control" required placeholder="Contoh: Budi Santoso">
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Kerja</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Buka Shift & Masuk Sistem</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>