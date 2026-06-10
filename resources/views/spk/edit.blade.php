<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data SPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .form-control { border-color: #ced4da; }
        .form-control:focus { border-color: #0d6efd; box-shadow: none; }
        .bg-flute { background-color: #fff3cd; } 
        .hasil-kg-posisi { font-size: 13px; color: #198754; margin-top: 5px; }
    </style>
</head>
<body>

<div class="container py-4" style="max-width: 800px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url('/hitung-spk/riwayat') }}" class="btn btn-outline-dark fw-bold shadow-sm">⬅️ KEMBALI</a>
        <h3 class="fw-bold mb-0">✏️ Edit Data SPK</h3>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger fw-bold shadow-sm mb-4">
            ⚠️ Gagal Menyimpan! Pastikan input wajib sudah diisi.
        </div>
    @endif

    <form id="form-edit-spk" action="{{ url('/hitung-spk/update/' . $spk->id) }}" method="POST">
        @csrf
        <div class="card shadow-sm border-warning mb-4" style="border-width: 2px;">
            <div class="card-header bg-dark text-white fw-bold fs-5">
                Form Revisi SPK: <span class="text-warning">{{ $spk->no_spk }}</span>
            </div>
            
            <div class="card-body bg-white">
                <div class="row g-2 mb-3 pb-3 border-bottom">
                    <div class="col-md-4">
                        <label class="fw-bold small text-muted">NOMOR SPK / CUSTOM</label>
                        <input type="text" name="no_spk" class="form-control fw-bold text-uppercase" value="{{ old('no_spk', $spk->no_spk) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold small text-muted">LEBAR KERTAS (cm)</label>
                        <div class="input-group">
                            <input type="number" name="lebar_cm" id="input-lebar" class="form-control fw-bold text-center" onkeyup="hitungEdit()" onchange="hitungEdit()" value="{{ old('lebar_cm', $spk->lebar_cm) }}" required>
                            <span class="input-group-text">cm</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold small text-muted">PANJANG LARI (Meter)</label>
                        <div class="input-group">
                            <input type="number" name="panjang_m" id="input-panjang" class="form-control fw-bold text-center" onkeyup="hitungEdit()" onchange="hitungEdit()" value="{{ old('panjang_m', $spk->panjang_m) }}" required>
                            <span class="input-group-text">m</span>
                        </div>
                    </div>
                </div>

                <div class="row g-2 align-items-end text-center small mb-1">
                    <div class="col"><label class="fw-bold">DB (1.0)</label></div>
                    <div class="col">
                        <label class="fw-bold text-primary mb-1">BM (Faktor)</label>
                        <input type="number" step="0.01" name="faktor_bm" id="input-faktor-bm" class="form-control form-control-sm text-center fw-bold text-primary mx-auto" value="{{ old('faktor_bm', $spk->faktor_bm) }}" style="width: 70px;" onkeyup="hitungEdit()" onchange="hitungEdit()"> 
                    </div>
                    <div class="col"><label class="fw-bold">BL (1.0)</label></div>
                    <div class="col">
                        <label class="fw-bold text-primary mb-1">CM (Faktor)</label>
                        <input type="number" step="0.01" name="faktor_cm" id="input-faktor-cm" class="form-control form-control-sm text-center fw-bold text-primary mx-auto" value="{{ old('faktor_cm', $spk->faktor_cm) }}" style="width: 70px;" onkeyup="hitungEdit()" onchange="hitungEdit()">
                    </div>
                    <div class="col"><label class="fw-bold">CL (1.0)</label></div>
                    <div class="col-2"><label class="fw-bold text-success">TOTAL SPK INI</label></div>
                </div>
                
                <div class="row g-2 text-center align-items-start">
                    <div class="col">
                        <input type="number" name="gsm_db" id="input-db" class="form-control fw-bold text-center" onkeyup="hitungEdit()" value="{{ old('gsm_db', $spk->gsm_db) }}">
                        <div class="hasil-kg-posisi fw-bold"><span id="kg-db">0.00</span> Kg</div>
                    </div>
                    <div class="col">
                        <input type="number" name="gsm_bm" id="input-bm" class="form-control fw-bold text-center bg-flute" onkeyup="hitungEdit()" value="{{ old('gsm_bm', $spk->gsm_bm) }}">
                        <div class="hasil-kg-posisi fw-bold"><span id="kg-bm">0.00</span> Kg</div>
                    </div>
                    <div class="col">
                        <input type="number" name="gsm_bl" id="input-bl" class="form-control fw-bold text-center" onkeyup="hitungEdit()" value="{{ old('gsm_bl', $spk->gsm_bl) }}">
                        <div class="hasil-kg-posisi fw-bold"><span id="kg-bl">0.00</span> Kg</div>
                    </div>
                    <div class="col">
                        <input type="number" name="gsm_cm" id="input-cm" class="form-control fw-bold text-center bg-flute" onkeyup="hitungEdit()" value="{{ old('gsm_cm', $spk->gsm_cm) }}">
                        <div class="hasil-kg-posisi fw-bold"><span id="kg-cm">0.00</span> Kg</div>
                    </div>
                    <div class="col">
                        <input type="number" name="gsm_cl" id="input-cl" class="form-control fw-bold text-center" onkeyup="hitungEdit()" value="{{ old('gsm_cl', $spk->gsm_cl) }}">
                        <div class="hasil-kg-posisi fw-bold"><span id="kg-cl">0.00</span> Kg</div>
                    </div>
                    <div class="col-2">
                        <input type="text" id="total-kg-card" class="form-control form-control-lg fw-bold text-center text-success bg-light border-success" value="0.00" readonly>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-light text-end p-3">
                <button type="submit" class="btn btn-warning fw-bold px-4 shadow-sm">🔄 UPDATE DATA</button>
            </div>
        </div>
    </form>

</div>

<script>
    // Jalankan hitungan otomatis saat halaman edit pertama kali dibuka
    window.addEventListener('DOMContentLoaded', hitungEdit);

    function hitungEdit() {
        let lebarM = (parseFloat(document.getElementById('input-lebar').value) || 0) / 100;
        let panjangM = parseFloat(document.getElementById('input-panjang').value) || 0;

        let fBM = parseFloat(document.getElementById('input-faktor-bm').value) || 1.36;
        let fCM = parseFloat(document.getElementById('input-faktor-cm').value) || 1.46;

        let gDB = parseFloat(document.getElementById('input-db').value) || 0;
        let gBM = parseFloat(document.getElementById('input-bm').value) || 0;
        let gBL = parseFloat(document.getElementById('input-bl').value) || 0;
        let gCM = parseFloat(document.getElementById('input-cm').value) || 0;
        let gCL = parseFloat(document.getElementById('input-cl').value) || 0;

        let kgDB = (panjangM * lebarM * gDB * 1.0) / 1000;
        let kgBM = (panjangM * lebarM * gBM * fBM) / 1000;
        let kgBL = (panjangM * lebarM * gBL * 1.0) / 1000;
        let kgCM = (panjangM * lebarM * gCM * fCM) / 1000;
        let kgCL = (panjangM * lebarM * gCL * 1.0) / 1000;

        document.getElementById('kg-db').innerText = kgDB.toFixed(2);
        document.getElementById('kg-bm').innerText = kgBM.toFixed(2);
        document.getElementById('kg-bl').innerText = kgBL.toFixed(2);
        document.getElementById('kg-cm').innerText = kgCM.toFixed(2);
        document.getElementById('kg-cl').innerText = kgCL.toFixed(2);

        let totalCard = kgDB + kgBM + kgBL + kgCM + kgCL;
        document.getElementById('total-kg-card').value = totalCard.toFixed(2);
    }
</script>

</body>
</html>