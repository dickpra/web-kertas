<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Produksi Corrugator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .menu-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 12px;
            border: none;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
            cursor: pointer;
        }
        .icon-box {
            font-size: 50px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container py-5" style="max-width: 900px;">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark">DASHBOARD PRODUKSI</h2>
        <p class="text-muted">Pilih modul aplikasi yang ingin digunakan</p>
    </div>

    <div class="row g-4">
        
        <!-- MENU 1: SCAN SHIFT ROLL -->
        <div class="col-md-6 col-lg-4">
            <a href="{{ url('/shift') }}" class="text-decoration-none">
                <div class="card menu-card shadow-sm h-100 p-4 text-center">
                    <div class="icon-box">🚜</div>
                    <h5 class="fw-bold text-dark">Scan Shift Roll</h5>
                    <p class="text-muted small mb-0">Catat pemakaian kertas roll harian per shift oleh Forklift.</p>
                </div>
            </a>
        </div>

        <!-- MENU 2: HITUNG KEBUTUHAN SPK -->
        <div class="col-md-6 col-lg-4">
            <a href="{{ url('/hitung-spk') }}" class="text-decoration-none">
                <div class="card menu-card shadow-sm h-100 p-4 text-center">
                    <div class="icon-box">🧮</div>
                    <h5 class="fw-bold text-dark">Hitung Kebutuhan SPK</h5>
                    <p class="text-muted small mb-0">Kalkulasi tonase & kombinasi roll mesin Corrugator per SPK.</p>
                </div>
            </a>
        </div>

        <!-- MENU 3: DATA MASTER -->
        <div class="col-md-6 col-lg-4">
            <a href="{{ url('/search') }}" class="text-decoration-none">
                <div class="card menu-card shadow-sm h-100 p-4 text-center">
                    <div class="icon-box">📦</div>
                    <h5 class="fw-bold text-dark">Data Stock Kertas</h5>
                    <p class="text-muted small mb-0">Lihat seluruh database master roll yang tersedia di gudang.</p>
                </div>
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>