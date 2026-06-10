<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Batch SPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .badge-spk { font-size: 0.85rem; padding: 0.4em 0.6em; margin-bottom: 4px; display: inline-block; }
    </style>
</head>
<body class="bg-light">

<div class="container py-4" style="max-width: 1100px;">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <a href="{{ url('/hitung-spk') }}" class="btn btn-outline-dark fw-bold shadow-sm">⬅️ KEMBALI KE MENU</a>
        <h3 class="fw-bold mb-0">📋 Riwayat Sesi Kalkulasi SPK</h3>
        <a href="{{ url('/hitung-spk/manual') }}" class="btn btn-primary fw-bold shadow-sm">➕ INPUT SESI BARU</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success fw-bold shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <form action="{{ url('/hitung-spk/riwayat') }}" method="GET">
                <div class="input-group shadow-sm">
                    <input type="text" name="search" class="form-control form-control-lg border-dark" placeholder="Cari No SPK atau Kode Sesi..." value="{{ $search ?? '' }}">
                    <button class="btn btn-dark fw-bold px-4" type="submit">🔍 CARI</button>
                    @if(!empty($search))
                        <a href="{{ url('/hitung-spk/riwayat') }}" class="btn btn-danger fw-bold d-flex align-items-center">✖</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th width="20%">Info Sesi</th>
                            <th width="40%">Daftar SPK (Isi JSON)</th>
                            <th width="20%">Total Aktual (Kg)</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kalkulasis as $sesi)
                        <tr>
                            <td class="text-center">
                                <div class="fw-bold text-dark">{{ $sesi->created_at->format('d M Y') }}</div>
                                <div class="small text-muted mb-1">{{ $sesi->created_at->format('H:i') }} WIB</div>
                                <span class="badge bg-secondary">{{ $sesi->kode_sesi }}</span>
                            </td>
                            
                            <td class="text-start px-4">
                                @php
                                    // Menghitung berapa SPK di dalam sesi ini
                                    $jumlahSpk = count($sesi->data_spk);
                                @endphp
                                <div class="fw-bold text-primary mb-2">Terdiri dari {{ $jumlahSpk }} SPK:</div>
                                
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($sesi->data_spk as $spk)
                                        <div class="border rounded bg-white px-2 py-1 shadow-sm text-center" style="font-size: 0.8rem;">
                                            <strong class="text-dark">{{ $spk['no_spk'] }}</strong><br>
                                            <span class="text-muted">{{ $spk['lebar_cm'] }}cm x {{ $spk['panjang_m'] }}m</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-danger fs-5 shadow-sm">{{ number_format($sesi->total_aktual_semua, 2) }} Kg</span>
                            </td>

                            <td class="text-center">
                                <form action="{{ url('/hitung-spk/delete/'.$sesi->id) }}" method="POST" onsubmit="return confirm('Yakin ingin MENGHAPUS SELURUH SESI BATCH ({{ $jumlahSpk }} SPK) ini?')">
                                    @csrf
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ url('/hitung-spk/edit/'.$sesi->id) }}" class="btn btn-warning fw-bold">✏️ EDIT SESI</a>
                                        <button type="submit" class="btn btn-danger fw-bold">❌</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-5 text-center text-muted fs-5">
                                @if(!empty($search))
                                    Data Sesi dengan kata kunci <strong>"{{ $search }}"</strong> tidak ditemukan.
                                @else
                                    Belum ada data Kalkulasi SPK yang disimpan.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $kalkulasis->links() }}
    </div>

</div>

</body>
</html>