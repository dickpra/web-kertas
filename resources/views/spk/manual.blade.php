<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Rinci Per SPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .form-control { border-color: #ced4da; }
        .form-control:focus { border-color: #0d6efd; box-shadow: none; }
        .bg-flute { background-color: #fff3cd !important; } 
        .spk-card { border-left: 5px solid #0d6efd; transition: all 0.3s ease; }
        
        /* Styling khusus untuk Grid Tabel di dalam Card */
        .grid-header { background-color: #e9ecef; border-radius: 5px 5px 0 0; }
        .input-readonly { background-color: transparent !important; border: 1px dashed #ced4da; cursor: not-allowed; }
        .input-aktual { background-color: #fff5f5 !important; border-color: #dc3545 !important; color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>

<div class="container py-4" style="max-width: 1000px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url('/hitung-spk') }}" class="btn btn-outline-dark fw-bold shadow-sm">⬅️ KEMBALI</a>
        <h3 class="fw-bold mb-0">🧮 Kalkulator SPK (Dengan Proporsional)</h3>
        <button type="button" class="btn btn-success fw-bold shadow-sm" onclick="simpanData()">💾 SAVE SEMUA</button>        
    </div>

    @if ($errors->any())
        <div class="alert alert-danger fw-bold shadow-sm mb-4">
            ⚠️ Gagal Menyimpan! Pastikan semua input wajib terisi.
        </div>
    @endif

    <form id="form-spk-multi" action="{{ url('/hitung-spk/manual/store') }}" method="POST">
        @csrf
        <div id="spk-container">
            
            @php $oldSpks = old('no_spk', ['']); @endphp

            @foreach($oldSpks as $index => $spk_val)
            <div class="card shadow-sm border-0 mb-4 spk-card" id="spk-{{ $index + 1 }}">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold fs-5 judul-spk">SPK #{{ $index + 1 }}</span>
                    <div>
                        <button type="button" class="btn btn-sm btn-warning fw-bold me-2" onclick="cloneCard(this)">📄 CLONE</button>
                        <button type="button" class="btn btn-sm btn-danger fw-bold btn-hapus" onclick="hapusCard(this)" {{ count($oldSpks) == 1 ? 'disabled' : '' }}>❌ HAPUS</button>
                    </div>
                </div>
                <div class="card-body bg-white">
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="fw-bold small text-muted">NOMOR SPK / CUSTOM</label>
                            <input type="text" name="no_spk[]" class="form-control fw-bold text-uppercase" placeholder="Cth: CIPTA" value="{{ old('no_spk.'.$index) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold small text-muted">LEBAR KERTAS (cm)</label>
                            <div class="input-group">
                                <input type="number" name="lebar_cm[]" class="form-control fw-bold text-center input-lebar" onkeyup="hitungKalkulator()" onchange="hitungKalkulator()" value="{{ old('lebar_cm.'.$index) }}" required>
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold small text-muted">PANJANG LARI (Meter)</label>
                            <div class="input-group">
                                <input type="number" name="panjang_m[]" class="form-control fw-bold text-center input-panjang" onkeyup="hitungKalkulator()" onchange="hitungKalkulator()" value="{{ old('panjang_m.'.$index) }}" required>
                                <span class="input-group-text">m</span>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded p-3 bg-white shadow-sm">
                        
                        <div class="row g-2 text-center align-items-end fw-bold small grid-header p-2 mb-2">
                            <div class="col-2 text-start text-muted">PARAMETER</div>
                            <div class="col">DB (1.0)</div>
                            <div class="col text-primary">
                                BM (Faktor)<br>
                                <input type="number" step="0.01" name="faktor_bm[]" class="form-control form-control-sm text-center text-primary fw-bold mx-auto mt-1 input-faktor-bm" value="{{ old('faktor_bm.'.$index, '1.35') }}" style="width: 60px;" onkeyup="hitungKalkulator()" onchange="hitungKalkulator()"> 
                            </div>
                            <div class="col">BL (1.0)</div>
                            <div class="col text-primary">
                                CM (Faktor)<br>
                                <input type="number" step="0.01" name="faktor_cm[]" class="form-control form-control-sm text-center text-primary fw-bold mx-auto mt-1 input-faktor-cm" value="{{ old('faktor_cm.'.$index, '1.43') }}" style="width: 60px;" onkeyup="hitungKalkulator()" onchange="hitungKalkulator()">
                            </div>
                            <div class="col">CL (1.0)</div>
                            <div class="col-2 text-success">TOTAL SPK</div>
                        </div>

                        <div class="row g-2 text-center align-items-center mb-2">
                            <div class="col-2 text-start fw-bold small text-muted">1. INPUT GSM</div>
                            <div class="col"><input type="number" name="gsm_db[]" class="form-control fw-bold text-center input-db" placeholder="GSM" onkeyup="hitungKalkulator()" value="{{ old('gsm_db.'.$index) }}"></div>
                            <div class="col"><input type="number" name="gsm_bm[]" class="form-control fw-bold text-center bg-flute input-bm" placeholder="GSM" onkeyup="hitungKalkulator()" value="{{ old('gsm_bm.'.$index) }}"></div>
                            <div class="col"><input type="number" name="gsm_bl[]" class="form-control fw-bold text-center input-bl" placeholder="GSM" onkeyup="hitungKalkulator()" value="{{ old('gsm_bl.'.$index) }}"></div>
                            <div class="col"><input type="number" name="gsm_cm[]" class="form-control fw-bold text-center bg-flute input-cm" placeholder="GSM" onkeyup="hitungKalkulator()" value="{{ old('gsm_cm.'.$index) }}"></div>
                            <div class="col"><input type="number" name="gsm_cl[]" class="form-control fw-bold text-center input-cl" placeholder="GSM" onkeyup="hitungKalkulator()" value="{{ old('gsm_cl.'.$index) }}"></div>
                            <div class="col-2"></div>
                        </div>

                        <div class="row g-2 text-center align-items-center mb-3">
                            <div class="col-2 text-start fw-bold small text-secondary">2. TEORI (Kg)</div>
                            <div class="col"><input type="text" class="form-control text-center input-readonly kg-db" value="0.00" readonly tabindex="-1"></div>
                            <div class="col"><input type="text" class="form-control text-center input-readonly kg-bm" value="0.00" readonly tabindex="-1"></div>
                            <div class="col"><input type="text" class="form-control text-center input-readonly kg-bl" value="0.00" readonly tabindex="-1"></div>
                            <div class="col"><input type="text" class="form-control text-center input-readonly kg-cm" value="0.00" readonly tabindex="-1"></div>
                            <div class="col"><input type="text" class="form-control text-center input-readonly kg-cl" value="0.00" readonly tabindex="-1"></div>
                            <div class="col-2"><input type="text" class="form-control text-center fw-bold input-readonly text-secondary total-teori-card" value="0.00" readonly tabindex="-1"></div>
                        </div>

                        <div class="row g-2 text-center align-items-center pt-2 border-top border-danger">
                            <div class="col-2 text-start fw-bold small text-danger">3. AKTUAL (Kg)</div>
                            <div class="col"><input type="text" name="aktual_db[]" class="form-control text-center input-aktual akt-db" value="0.00" readonly tabindex="-1"></div>
                            <div class="col"><input type="text" name="aktual_bm[]" class="form-control text-center input-aktual akt-bm" value="0.00" readonly tabindex="-1"></div>
                            <div class="col"><input type="text" name="aktual_bl[]" class="form-control text-center input-aktual akt-bl" value="0.00" readonly tabindex="-1"></div>
                            <div class="col"><input type="text" name="aktual_cm[]" class="form-control text-center input-aktual akt-cm" value="0.00" readonly tabindex="-1"></div>
                            <div class="col"><input type="text" name="aktual_cl[]" class="form-control text-center input-aktual akt-cl" value="0.00" readonly tabindex="-1"></div>
                            <div class="col-2"><input type="text" name="total_kg_aktual[]" class="form-control form-control-lg text-center fw-bold text-white bg-danger border-danger total-aktual-card" value="0.00" readonly tabindex="-1"></div>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
            
        </div>

        <div class="text-center mb-5">
            <button type="button" class="btn btn-outline-primary fw-bold px-5 py-2 shadow-sm" onclick="tambahCardKosong()">➕ TAMBAH SPK KOSONG</button>
        </div>

        <div class="card shadow-sm border-dark mb-4">
            <div class="card-header bg-dark text-white fw-bold fs-5 text-center">
                📊 GRAND TOTAL (TEORI VS AKTUAL TIMBANGAN)
            </div>
            <div class="card-body">
                <div class="row text-center fw-bold mb-3 border-bottom pb-2">
                    <div class="col-2 text-muted"></div>
                    <div class="col-2">DB</div>
                    <div class="col-2">BM</div>
                    <div class="col-2">BL</div>
                    <div class="col-2">CM</div>
                    <div class="col-2">CL</div>
                </div>
                
                <div class="row text-center fw-bold fs-5 align-items-center mb-3">
                    <div class="col-2 fs-6 text-muted text-end">TOTAL TEORI :</div>
                    <div class="col-2 text-secondary" id="gt_db">0.00</div>
                    <div class="col-2 text-secondary" id="gt_bm">0.00</div>
                    <div class="col-2 text-secondary" id="gt_bl">0.00</div>
                    <div class="col-2 text-secondary" id="gt_cm">0.00</div>
                    <div class="col-2 text-secondary" id="gt_cl">0.00</div>
                </div>

                <div class="row text-center fw-bold fs-5 align-items-center bg-light p-3 rounded border">
                    <div class="col-2 fs-6 text-danger text-end">INPUT AKTUAL (KG) :<br><small class="text-muted fw-normal">Isi dari data scanner</small></div>
                    <div class="col-2"><input type="number" step="0.01" name="global_aktual_db" class="form-control fw-bold text-center text-danger border-danger aktual-global" id="akt_global_db" placeholder="Kg Aktual" value="{{ old('global_aktual_db') }}" onkeyup="hitungKalkulator()" onchange="hitungKalkulator()"></div>
                    <div class="col-2"><input type="number" step="0.01" name="global_aktual_bm" class="form-control fw-bold text-center text-danger border-danger aktual-global" id="akt_global_bm" placeholder="Kg Aktual" value="{{ old('global_aktual_bm') }}" onkeyup="hitungKalkulator()" onchange="hitungKalkulator()"></div>
                    <div class="col-2"><input type="number" step="0.01" name="global_aktual_bl" class="form-control fw-bold text-center text-danger border-danger aktual-global" id="akt_global_bl" placeholder="Kg Aktual" value="{{ old('global_aktual_bl') }}" onkeyup="hitungKalkulator()" onchange="hitungKalkulator()"></div>
                    <div class="col-2"><input type="number" step="0.01" name="global_aktual_cm" class="form-control fw-bold text-center text-danger border-danger aktual-global" id="akt_global_cm" placeholder="Kg Aktual" value="{{ old('global_aktual_cm') }}" onkeyup="hitungKalkulator()" onchange="hitungKalkulator()"></div>
                    <div class="col-2"><input type="number" step="0.01" name="global_aktual_cl" class="form-control fw-bold text-center text-danger border-danger aktual-global" id="akt_global_cl" placeholder="Kg Aktual" value="{{ old('global_aktual_cl') }}" onkeyup="hitungKalkulator()" onchange="hitungKalkulator()"></div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let cardCount = {{ count($oldSpks) }};
    window.addEventListener('DOMContentLoaded', hitungKalkulator);

    // FUNGSI SAKTI: Merapikan ulang nomor urut SPK setiap kali ada penambahan/penghapusan
    function reindexSPK() {
        let cards = document.querySelectorAll('.spk-card');
        cards.forEach((card, index) => {
            let nomorUrut = index + 1;
            card.id = "spk-" + nomorUrut;
            card.querySelector('.judul-spk').innerText = "SPK #" + nomorUrut;
        });
    }

    function hitungKalkulator() {
        let cards = document.querySelectorAll('.spk-card');
        
        let gtDB = 0, gtBM = 0, gtBL = 0, gtCM = 0, gtCL = 0;
        let totalMeterAll = 0; 

        // TAHAP 1: Hitung Teori
        cards.forEach(card => {
            let lebarM = (parseFloat(card.querySelector('.input-lebar').value) || 0) / 100;
            let panjangM = parseFloat(card.querySelector('.input-panjang').value) || 0;
            totalMeterAll += panjangM;

            let fBM = parseFloat(card.querySelector('.input-faktor-bm').value) || 1.35;
            let fCM = parseFloat(card.querySelector('.input-faktor-cm').value) || 1.43;

            let gDB = parseFloat(card.querySelector('.input-db').value) || 0;
            let gBM = parseFloat(card.querySelector('.input-bm').value) || 0;
            let gBL = parseFloat(card.querySelector('.input-bl').value) || 0;
            let gCM = parseFloat(card.querySelector('.input-cm').value) || 0;
            let gCL = parseFloat(card.querySelector('.input-cl').value) || 0;

            let kgDB = (panjangM * lebarM * gDB * 1.0) / 1000;
            let kgBM = (panjangM * lebarM * gBM * fBM) / 1000;
            let kgBL = (panjangM * lebarM * gBL * 1.0) / 1000;
            let kgCM = (panjangM * lebarM * gCM * fCM) / 1000;
            let kgCL = (panjangM * lebarM * gCL * 1.0) / 1000;

            card.querySelector('.kg-db').value = kgDB.toFixed(2);
            card.querySelector('.kg-bm').value = kgBM.toFixed(2);
            card.querySelector('.kg-bl').value = kgBL.toFixed(2);
            card.querySelector('.kg-cm').value = kgCM.toFixed(2);
            card.querySelector('.kg-cl').value = kgCL.toFixed(2);
            
            let totalTeoriCard = kgDB + kgBM + kgBL + kgCM + kgCL;
            card.querySelector('.total-teori-card').value = totalTeoriCard.toFixed(2);

            gtDB += kgDB; gtBM += kgBM; gtBL += kgBL; gtCM += kgCM; gtCL += kgCL;
        });

        document.getElementById('gt_db').innerText = gtDB.toFixed(2);
        document.getElementById('gt_bm').innerText = gtBM.toFixed(2);
        document.getElementById('gt_bl').innerText = gtBL.toFixed(2);
        document.getElementById('gt_cm').innerText = gtCM.toFixed(2);
        document.getElementById('gt_cl').innerText = gtCL.toFixed(2);

        // TAHAP 2: Hitung Alokasi Prorate
        let aktDB = parseFloat(document.getElementById('akt_global_db').value) || gtDB;
        let aktBM = parseFloat(document.getElementById('akt_global_bm').value) || gtBM;
        let aktBL = parseFloat(document.getElementById('akt_global_bl').value) || gtBL;
        let aktCM = parseFloat(document.getElementById('akt_global_cm').value) || gtCM;
        let aktCL = parseFloat(document.getElementById('akt_global_cl').value) || gtCL;

        cards.forEach(card => {
            let panjangM = parseFloat(card.querySelector('.input-panjang').value) || 0;
            let rasio = totalMeterAll > 0 ? (panjangM / totalMeterAll) : 0;

            let gDB = parseFloat(card.querySelector('.input-db').value) || 0;
            let gBM = parseFloat(card.querySelector('.input-bm').value) || 0;
            let gBL = parseFloat(card.querySelector('.input-bl').value) || 0;
            let gCM = parseFloat(card.querySelector('.input-cm').value) || 0;
            let gCL = parseFloat(card.querySelector('.input-cl').value) || 0;

            let jatahDB = gDB > 0 ? (rasio * aktDB) : 0;
            let jatahBM = gBM > 0 ? (rasio * aktBM) : 0;
            let jatahBL = gBL > 0 ? (rasio * aktBL) : 0;
            let jatahCM = gCM > 0 ? (rasio * aktCM) : 0;
            let jatahCL = gCL > 0 ? (rasio * aktCL) : 0;

            card.querySelector('.akt-db').value = jatahDB.toFixed(2);
            card.querySelector('.akt-bm').value = jatahBM.toFixed(2);
            card.querySelector('.akt-bl').value = jatahBL.toFixed(2);
            card.querySelector('.akt-cm').value = jatahCM.toFixed(2);
            card.querySelector('.akt-cl').value = jatahCL.toFixed(2);

            let totalAktualCard = jatahDB + jatahBM + jatahBL + jatahCM + jatahCL;
            card.querySelector('.total-aktual-card').value = totalAktualCard.toFixed(2);
        });
    }

    function simpanData() {
        let form = document.getElementById('form-spk-multi');
        if (form.reportValidity()) { form.submit(); }
    }

    function cloneCard(btn) {
        let cardAsli = btn.closest('.spk-card');
        let inputsAsli = cardAsli.querySelectorAll('input');
        let values = Array.from(inputsAsli).map(input => input.value);

        let cardBaru = cardAsli.cloneNode(true);
        let inputsBaru = cardBaru.querySelectorAll('input');
        inputsBaru.forEach((input, index) => { input.value = values[index]; });
        
        // Hapus "cardCount++" (Sudah tidak kita pakai)
        cardBaru.querySelector('input[name="no_spk[]"]').value = "";
        
        document.getElementById('spk-container').appendChild(cardBaru);
        
        reindexSPK(); // PANGGIL DI SINI!
        hitungKalkulator();
        updateTombolHapus();
    }

    function tambahCardKosong() {
        let cardPertama = document.querySelector('.spk-card');
        let cardBaru = cardPertama.cloneNode(true);
        
        // Hapus "cardCount++"
        let inputs = cardBaru.querySelectorAll('input');
        inputs.forEach(input => {
            if(!input.classList.contains('input-faktor-bm') && !input.classList.contains('input-faktor-cm')) {
                input.value = "";
            }
        });
        
        cardBaru.querySelector('.total-teori-card').value = "0.00";
        cardBaru.querySelector('.total-aktual-card').value = "0.00";
        document.getElementById('spk-container').appendChild(cardBaru);
        
        reindexSPK(); // PANGGIL DI SINI!
        updateTombolHapus();
    }

    function hapusCard(btn) {
        let card = btn.closest('.spk-card');
        card.remove();
        
        reindexSPK(); // PANGGIL DI SINI!
        hitungKalkulator();
        updateTombolHapus();
    }

    function updateTombolHapus() {
        let cards = document.querySelectorAll('.spk-card');
        let btns = document.querySelectorAll('.btn-hapus');
        if (cards.length === 1) { btns[0].disabled = true; } else { btns.forEach(btn => btn.disabled = false); }
    }
</script>

</body>
</html>