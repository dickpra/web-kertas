<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Import Data Stock Kertas</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f4f6f9; 
        }
        .form-control-lg {
            border: 2px solid #ced4da;
            height: 55px;
        }
        .form-control-lg:focus {
            border-color: #0d6efd;
            box-shadow: none;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <a href="{{ url('/search') }}" class="btn btn-outline-dark fw-bold mb-3 shadow-sm">
                ⬅️ KEMBALI
            </a>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-dark text-white text-center p-3 fw-bold border-0" style="font-size: 16px; letter-spacing: 1px;">
                    📥 IMPORT STOK KERTAS (CSV)
                </div>
                
                <div class="card-body p-4 bg-white rounded-bottom-3">
                    
                    @if(session('success'))
                        <div class="alert alert-success text-center fw-bold shadow-sm" role="alert">
                            ✅ {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger text-center fw-bold shadow-sm" role="alert">
                            ❌ {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ url('/stock-kertas/import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary" style="font-size: 14px;">PILIH FILE LAPORAN</label>
                            <input type="file" name="file_csv" class="form-control form-control-lg border-dark" accept=".csv" required>
                            
                            <div class="form-text mt-2 text-muted" style="font-size: 13px;">
                                💡 <strong>Catatan:</strong> Pastikan file yang diupload adalah hasil ekspor ke format <b>CSV</b> dengan pemisah titik koma (;).
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm" style="height: 55px; font-size: 16px;">
                            🚀 UPLOAD & SINKRONISASI
                        </button>
                    </form>

                </div>
            </div>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>