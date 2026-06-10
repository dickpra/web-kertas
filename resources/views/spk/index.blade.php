<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Hitung SPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .card-menu {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        .card-menu:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
    </style>
</head>
<body>

<div class="container py-5" style="max-width: 800px;">
    
    <div class="mb-4">
        <a href="{{ url('/') }}" class="btn btn-outline-dark fw-bold shadow-sm">
            ⬅️ KEMBALI KE DASHBOARD
        </a>
    </div>

    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark">BAGI PEMAKAIAN ROLL PER SPK</h2>
        <p class="text-muted">Pilih metode perhitungan untuk mengalokasikan beban pemakaian kertas</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <a href="{{ url('/hitung-spk/otomatis') }}" class="card card-menu shadow-sm h-100 p-4 border-0">
                <div class="text-center mb-3">
                    <span style="font-size: 50px;">⚙️</span>
                </div>
                <h4 class="fw-bold text-primary text-center">Sistem Otomatis</h4>
                <hr>
                <ul class="text-muted small mb-0 ps-3">
                    <li class="mb-2">Hitungan menggunakan rumus <strong>(Panjang × Lebar × GSM)</strong>.</li>
                    <li class="mb-2">Sistem otomatis membagi sisa waste secara <strong>proporsional</strong> ke setiap SPK.</li>
                    <li>Sangat cocok untuk roll yang dipakai jalan nyambung (continuous) untuk 2-5 SPK sekaligus.</li>
                </ul>
            </a>
        </div>

        <div class="col-md-6">
            <a href="{{ url('/hitung-spk/manual') }}" class="card card-menu shadow-sm h-100 p-4 border-0">
                <div class="text-center mb-3">
                    <span style="font-size: 50px;">✍️</span>
                </div>
                <h4 class="fw-bold text-success text-center">Input Manual</h4>
                <hr>
                <ul class="text-muted small mb-0 ps-3">
                    <li class="mb-2">Admin langsung mengetik beban <strong>Kg per SPK</strong> secara bebas.</li>
                    <li class="mb-2">Tidak menggunakan rumus otomatis dari sistem.</li>
                    <li>Digunakan untuk kasus khusus (contoh: meteran mesin rusak, roll sisa/potongan, atau penyesuaian manual).</li>
                </ul>
            </a>
        </div>

        <div class="col-md-6">
            <a href="{{ url('/hitung-spk/manual') }}" class="card card-menu shadow-sm h-100 p-4 border-0">
                <div class="text-center mb-3">
                    <span style="font-size: 50px;">✍️</span>
                </div>
                <h4 class="fw-bold text-success text-center">Perkiraan Roll per SPK</h4>
                <hr>
                <ul class="text-muted small mb-0 ps-3">
                    {{-- <li class="mb-2">Admin langsung mengetik beban <strong>Kg per SPK</strong> secara bebas.</li>
                    <li class="mb-2">Tidak menggunakan rumus otomatis dari sistem.</li>
                    <li>Digunakan untuk kasus khusus (contoh: meteran mesin rusak, roll sisa/potongan, atau penyesuaian manual).</li> --}}
                </ul>
            </a>
        </div>

        <div class="col-md-12 mt-4">
            <a href="{{ url('/hitung-spk/riwayat') }}" class="card card-menu shadow-sm p-3 border-0 bg-dark text-white text-center">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <span style="font-size: 40px;">📋</span>
                    <div class="text-start">
                        <h4 class="fw-bold mb-1">Riwayat & Daftar SPK</h4>
                        <p class="small mb-0 text-white-50">Cari, edit, atau hapus data perhitungan SPK yang sudah tersimpan.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>