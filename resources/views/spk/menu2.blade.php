<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Kebutuhan SPK (Manual)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .form-control, .form-select { border-color: #ced4da; border-width: 2px; }
        .form-control:focus, .form-select:focus { border-color: #0d6efd; box-shadow: none; }
        .hasil-kg { font-size: 22px; font-weight: 900; color: #198754; background: #e8f5e9; border: 2px dashed #198754; }
    </style>
</head>
<body>

<div class="container py-4" style="max-width: 900px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url('/hitung-spk') }}" class="btn btn-outline-dark fw-bold shadow-sm">⬅️ KEMBALI</a>
        <h3 class="fw-bold mb-0">🧮 Kalkulator SPK (Input Mesin)</h3>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white fw-bold fs-5">
            1. SPESIFIKASI KERTAS (Lihat di Monitor)
        </div>
        <div class="card-body bg-white">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="fw-bold small text-muted">LEBAR (cm)</label>
                    <input type="number" id="lebar_cm" class="form-control form-control-lg fw-bold text-center" placeholder="Cth: 105" onkeyup="hitungSemua()" onchange="hitungSemua()" required>
                </div>
                <div class="col-md-4">
                    <label class="fw-bold small text-muted">GSM</label>
                    <input type="number" id="gsm" class="form-control form-control-lg fw-bold text-center" placeholder="Cth: 125" onkeyup="hitungSemua()" onchange="hitungSemua()" required>
                </div>
                <div class="col-md-4">
                    <label class="fw-bold small text-muted">POSISI MESIN</label>
                    <select id="posisi_mesin" class="form-select form-select-lg fw-bold text-center" onchange="hitungSemua()">
                        <option value="1.0">DB / BL / CL (Kertas Lurus)</option>
                        <option value="1.36">BM (Gelombang B - Factor 1.36)</option>
                        <option value="1.43">CM (Gelombang C - Factor 1.43)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center fs-5">
            <span>2. INPUT PANJANG LARI PER SPK</span>
            <button type="button" class="btn btn-sm btn-light fw-bold text-primary" onclick="tambahBaris()">+ TAMBAH SPK</button>
        </div>
        <div class="card-body bg-white p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="30%">Seq / No SPK</th>
                            <th width="25%">Panjang Lari (Meter)</th>
                            <th width="30%" class="text-center">Kebutuhan Kertas</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-spk">
                        <tr>
                            <td><input type="text" name="no_spk[]" class="form-control fw-bold text-uppercase" placeholder="Cth: 140 / CIPTA" required></td>
                            <td>
                                <div class="input-group">
                                    <input type="number" name="panjang_meter[]" class="form-control fw-bold input-meter" placeholder="Cth: 390" onkeyup="hitungSemua()" onchange="hitungSemua()" required>
                                    <span class="input-group-text">m</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <input type="text" class="form-control hasil-kg text-center" value="0.00 Kg" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm fw-bold hapus-baris" disabled>❌</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th class="text-end fs-5 py-3">TOTAL KESELURUHAN :</th>
                            <th>
                                <div class="input-group">
                                    <input type="text" id="total_meter" class="form-control fw-bold bg-white text-dark" value="0" readonly>
                                    <span class="input-group-text">m</span>
                                </div>
                            </th>
                            <th class="text-center py-3">
                                <div class="fs-4 fw-bold text-primary"><span id="total_kg">0.00</span> Kg</div>
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="button" class="btn btn-success btn-lg w-100 fw-bold shadow-sm" onclick="alert('Fitur simpan ke database menyusul jika dibutuhkan.')" style="height: 60px;">
            💾 SIMPAN DATA SPK
        </button>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Rumus: (Panjang x Lebar Meter x GSM x Faktor Flute) / 1000

    function hitungSemua() {
        // 1. Ambil Variabel Global
        let lebarCm = parseFloat(document.getElementById('lebar_cm').value) || 0;
        let gsm = parseFloat(document.getElementById('gsm').value) || 0;
        let factorFlute = parseFloat(document.getElementById('posisi_mesin').value) || 1;
        
        let lebarMeter = lebarCm / 100; // Konversi cm ke meter

        // 2. Looping setiap baris SPK
        let rows = document.querySelectorAll('#tbody-spk tr');
        let totalMeter = 0;
        let totalKg = 0;

        rows.forEach(row => {
            let meterInput = row.querySelector('.input-meter');
            let hasilOutput = row.querySelector('.hasil-kg');
            
            let meter = parseFloat(meterInput.value) || 0;
            
            // Hitung Kg untuk baris ini
            let kg = (meter * lebarMeter * gsm * factorFlute) / 1000;
            
            // Tampilkan hasil per baris
            hasilOutput.value = kg.toFixed(2) + " Kg";

            // Tambahkan ke Total
            totalMeter += meter;
            totalKg += kg;
        });

        // 3. Tampilkan Total Keseluruhan
        document.getElementById('total_meter').value = totalMeter;
        document.getElementById('total_kg').innerText = totalKg.toFixed(2);
    }

    // Fungsi Tambah Baris Baru
    function tambahBaris() {
        let tbody = document.getElementById('tbody-spk');
        let tr = document.createElement('tr');
        
        tr.innerHTML = `
            <td><input type="text" name="no_spk[]" class="form-control fw-bold text-uppercase" placeholder="No SPK..." required></td>
            <td>
                <div class="input-group">
                    <input type="number" name="panjang_meter[]" class="form-control fw-bold input-meter" placeholder="Meter..." onkeyup="hitungSemua()" onchange="hitungSemua()" required>
                    <span class="input-group-text">m</span>
                </div>
            </td>
            <td class="text-center">
                <input type="text" class="form-control hasil-kg text-center" value="0.00 Kg" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm fw-bold hapus-baris" onclick="hapusBaris(this)">❌</button>
            </td>
        `;
        tbody.appendChild(tr);
        cekTombolHapus();
    }

    // Fungsi Hapus Baris
    function hapusBaris(btn) {
        let row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
        hitungSemua();
        cekTombolHapus();
    }

    // Pastikan baris pertama tidak bisa dihapus jika hanya 1
    function cekTombolHapus() {
        let rows = document.querySelectorAll('#tbody-spk tr');
        let btns = document.querySelectorAll('.hapus-baris');
        if (rows.length === 1) {
            btns[0].disabled = true;
        } else {
            btns.forEach(btn => btn.disabled = false);
        }
    }
</script>

</body>
</html>