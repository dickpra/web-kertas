<!DOCTYPE html>
<html lang="id">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Shift Aktif - Monitoring Roll</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-light">
<div class="container py-4">

    <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm">
        <div>
            <h5 class="mb-0">Shift Aktif: <strong>{{ $shiftAktif->kepala_shift }}</strong></h5>
            <small class="text-muted">Tanggal: {{ $shiftAktif->tanggal }}</small>
        </div>
        <form action="{{ url('/shift/tutup/'.$shiftAktif->id) }}" method="POST">
            @csrf
            <button class="btn btn-danger btn-sm" onclick="return confirm('Tutup shift? Semua supir tidak bisa input di shift ini lagi.')">Tutup Shift</button>
        </form>
    </div>

    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-dark text-white fw-bold">1. Kamera Scanner</div>
                <div class="card-body p-0">
                    <div id="reader" style="width:100%"></div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white fw-bold">2. Input Manual (Jika Label Rusak)</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small fw-bold">Nomor Roll</label>
                        <input type="text" id="manual_no_roll" class="form-control" placeholder="Ketik No Roll...">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">Keterangan Hambatan</label>
                        <input type="text" id="manual_keterangan" class="form-control" placeholder="Contoh: Label sobek diserempet forklift">
                    </div>
                    <button class="btn btn-primary w-100" onclick="submitManual()">Kirim Input Manual</button>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white fw-bold">Daftar Roll pada Shift Ini</div>
                <div class="card-body p-0">
                    <table class="table table-hover table-striped mb-0 small">
                        <thead>
                            <tr class="table-dark text-center">
                                <th>No Roll</th>
                                <th>Jenis / GSM</th>
                                <th>Kilo Awal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi as $t)
                            <tr class="align-middle text-center">
                                <td>
                                    <strong>{{ $t->no_roll }}</strong>
                                    @if($t->metode_input == 'manual')
                                        <br><span class="badge bg-warning text-dark">Manual: {{ $t->keterangan }}</span>
                                    @endif
                                </td>
                                <td>{{ $t->masterKertas->jenis ?? '-' }} / {{ $t->masterKertas->gsm ?? '-' }}</td>
                                <td>{{ $t->sisa_kilo_awal }} Kg</td>
                                <td>
                                    @if($t->status == 'diambil')
                                        <span class="badge bg-danger">Dibawa Forklift</span>
                                    @else
                                        <span class="badge bg-success">Kembali (Sisa: {{ $t->sisa_kilo_akhir }} Kg)</span>
                                    @endif
                                </td>
                                <td>
                                    @if($t->status == 'diambil')
                                        <button class="btn btn-warning btn-xs text-white fw-bold" data-bs-toggle="modal" data-bs-target="#modalKembali{{ $t->id }}">Roll Balik</button>
                                        
                                        <div class="modal fade" id="modalKembali{{ $t->id }}" data-bs-backdrop="static" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                                <div class="modal-content">
                                                    <form action="{{ url('/shift/kembali-roll/'.$t->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header bg-warning text-white py-2"><h6 class="modal-title">Roll {{ $t->no_roll }} Balik</h6></div>
                                                        <div class="modal-body text-start">
                                                            <label class="mb-1 small fw-bold">Masukkan Sisa Timbangan (Kg):</label>
                                                            <input type="number" step="0.01" name="sisa_kilo_akhir" class="form-control" required placeholder="0.00">
                                                        </div>
                                                        <div class="modal-footer py-1">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-content="close" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada aktivitas forklift pada shift ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // 1. Eksekusi Handler Kirim Data Ke Server Laravel via AJAX
    function kirimDataRoll(noRoll, metodeInput, keteranganText = '') {
        fetch('/api/shift/ambil-roll', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ no_roll: noRoll, metode: metodeInput, keterangan: keteranganText })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2568/2568-84.wav');
                audio.play();
                alert(data.message);
                window.location.reload(); // Refresh halaman agar tabel terupdate
            } else {
                alert("Gagal: " + data.message);
                if(metodeInput === 'scan') startScanner(); // Restart scan jika error
            }
        }).catch(err => { alert("Koneksi Error"); });
    }

    // 2. Handler untuk SCANNER KAMERA
    let html5QrcodeScanner;
    function onScanSuccess(decodedText) {
        html5QrcodeScanner.clear(); // Matikan kamera sementara proses kirim
        kirimDataRoll(decodedText, 'scan');
    }

    function startScanner() {
        html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: { width: 280, height: 140 } });
        html5QrcodeScanner.render(onScanSuccess);
    }
    window.addEventListener('DOMContentLoaded', startScanner);

    // 3. Handler untuk INPUT MANUAL (Kondisi Label Rusak)
    function submitManual() {
        let noRoll = document.getElementById('manual_no_roll').value.trim();
        let ket = document.getElementById('manual_keterangan').value.trim();
        if(!noRoll) { alert("Nomor Roll wajib diisi manual!"); return; }
        if(!ket) { alert("Harap isi keterangan/alasan kenapa input manual!"); return; }
        
        kirimDataRoll(noRoll, 'manual', ket);
    }
</script>
</body>
</html>