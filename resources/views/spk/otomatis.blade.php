<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matching Otomatis (Monitor ke Forklift)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .bg-flute { background-color: #fff3cd !important; }
        .table-input th { font-size: 0.85rem; vertical-align: middle; }
        .table-input td { padding: 0.3rem; }
        .form-control-sm { font-weight: bold; text-align: center; }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid py-4 px-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url('/hitung-spk') }}" class="btn btn-outline-dark fw-bold shadow-sm">⬅️ KEMBALI</a>
        <h3 class="fw-bold mb-0">⚙️ Auto-Match: Monitor Mesin -> Forklift</h3>
        <button type="submit" form="formSapuJagat" class="btn btn-success fw-bold shadow-sm px-4">🚀 PROSES PENCOCOKAN DATA</button>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger fw-bold shadow-sm mb-4">⚠️ Pastikan semua kolom wajib diisi.</div>
    @endif

    <form action="{{ url('/hitung-spk/sapujagat/store') }}" method="POST" id="formSapuJagat">
        @csrf
        
        <div class="card shadow-sm border-dark mb-4" style="max-width: 600px;">
            <div class="card-header bg-dark text-white fw-bold">1. PILIH DATA SHIFT FORKLIFT</div>
            <div class="card-body bg-white">
                <select name="shift_id" class="form-select fw-bold border-dark" required>
                    <option value="">-- Pilih Laporan Shift (Sumber Berat Timbangan) --</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}">Shift {{ $shift->shift_ke }} | {{ \Carbon\Carbon::parse($shift->tanggal)->format('d-M-Y') }} | Opr: {{ $shift->kepala_shift }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card shadow-sm border-primary mb-4" style="border-width: 2px;">
            <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                <span>2. SALIN DAFTAR SPK DARI MONITOR CORRUGATOR</span>
                <button type="button" class="btn btn-sm btn-warning fw-bold text-dark shadow-sm" onclick="tambahSpk()">➕ TAMBAH BARIS</button>
            </div>
            <div class="card-body bg-white p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 align-middle table-input">
                        <thead class="table-light text-center">
                            <tr>
                                <th width="5%">Seq</th>
                                <th width="15%">Custom (SPK)</th>
                                <th width="8%">Width (Lebar)</th>
                                <th width="10%">Total Lari (m)</th>
                                <th width="8%">GSM DB</th>
                                <th width="8%" class="text-primary">GSM BM</th>
                                <th width="8%">GSM BL</th>
                                <th width="8%" class="text-primary">GSM CM</th>
                                <th width="8%">GSM CL</th>
                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-spk">
                            <tr>
                                <td><input type="text" name="seq[]" class="form-control form-control-sm" placeholder="10" required></td>
                                <td><input type="text" name="no_spk[]" class="form-control form-control-sm text-uppercase" placeholder="SUN PA" required></td>
                                <td><input type="number" name="lebar_mm[]" class="form-control form-control-sm" placeholder="1750" required></td>
                                <td><input type="number" name="panjang_m[]" class="form-control form-control-sm text-primary" placeholder="1991" required></td>
                                
                                <td><input type="text" name="gsm_db[]" class="form-control form-control-sm" placeholder="-"></td>
                                <td><input type="text" name="gsm_bm[]" class="form-control form-control-sm bg-flute" placeholder="-"></td>
                                <td><input type="text" name="gsm_bl[]" class="form-control form-control-sm" placeholder="-"></td>
                                <td><input type="text" name="gsm_cm[]" class="form-control form-control-sm bg-flute" placeholder="-"></td>
                                <td><input type="text" name="gsm_cl[]" class="form-control form-control-sm" placeholder="-"></td>
                                
                                <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm py-0 btn-hapus" onclick="hapusSpk(this)" disabled>❌</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light small text-muted">
                * Ketik sandi huruf dari monitor (misal: 160WS, 150SD).<br>
                * <b>Trik Tembak Ukuran:</b> Jika ada roll yang beda lebar dengan SPK, ketik pakai garis miring. Contoh: <code>127TF/165</code> (artinya kertas 127TF tapi pakai fisik lebar 165).<br>
                * Kosongkan kolom jika kertas tidak dipakai (misal Single Wall).
            </div>
        </div>
    </form>
</div>

<script>
    function tambahSpk() {
        let tbody = document.getElementById('tbody-spk');
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="seq[]" class="form-control form-control-sm" placeholder="Seq" required></td>
            <td><input type="text" name="no_spk[]" class="form-control form-control-sm text-uppercase" placeholder="SPK" required></td>
            <td><input type="number" name="lebar_mm[]" class="form-control form-control-sm" placeholder="Lebar" required></td>
            <td><input type="number" name="panjang_m[]" class="form-control form-control-sm text-primary" placeholder="Meter" required></td>
            <td><input type="text" name="gsm_db[]" class="form-control form-control-sm" placeholder="-"></td>
            <td><input type="text" name="gsm_bm[]" class="form-control form-control-sm bg-flute" placeholder="-"></td>
            <td><input type="text" name="gsm_bl[]" class="form-control form-control-sm" placeholder="-"></td>
            <td><input type="text" name="gsm_cm[]" class="form-control form-control-sm bg-flute" placeholder="-"></td>
            <td><input type="text" name="gsm_cl[]" class="form-control form-control-sm" placeholder="-"></td>
            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm py-0 btn-hapus" onclick="hapusSpk(this)">❌</button></td>
        `;
        tbody.appendChild(tr);
        cekTombolHapus();
        tr.querySelector('input[name="seq[]"]').focus();
    }
    function hapusSpk(btn) { btn.closest('tr').remove(); cekTombolHapus(); }
    function cekTombolHapus() {
        let btns = document.querySelectorAll('.btn-hapus');
        btns.forEach((btn, index) => btn.disabled = (btns.length === 1));
    }
</script>
</body>
</html>