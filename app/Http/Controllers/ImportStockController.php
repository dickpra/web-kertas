<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockKertas;
use App\Models\ShiftRoll;
use Illuminate\Support\Facades\DB;

class ImportStockController extends Controller
{
    /**
     * Menampilkan halaman upload CSV
     */
    public function showImportForm()
    {
        return view('stock.import'); 
    }

    /**
     * Memproses file CSV dan memasukkannya ke database
     */
    public function importStockCSV(Request $request)
    {
        // 1. Validasi file yang diupload wajib format CSV/TXT
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('file_csv')->getRealPath();
        $handle = fopen($file, "r");

        // Gunakan Transaction agar kalau di tengah jalan error, database tidak setengah-setengah masuknya
        DB::beginTransaction();
        try {
            $currentJenis = '';
            $currentGsm = '';
            $currentLebar = '';

            // 2. Looping pembacaan tiap baris CSV dengan pemisah titik koma (;)
            while (($data = fgetcsv($handle, 2000, ';')) !== FALSE) {
                
                // Lewati baris kosong di awal (seperti header FastReport)
                if (empty(trim($data[0]))) {
                    continue;
                }

                // 3. DETEKSI BARIS KELOMPOK (Jenis, GSM, Lebar)
                // Ciri: Index 0 terisi kode pendek (BAG, BEP), Index 1 kosong, Index 3 & 4 ada isi
                if (empty($data[1]) && !empty($data[3]) && !empty($data[4]) && strlen(trim($data[0])) <= 5) {
                    $currentJenis = trim($data[0]);
                    $currentGsm   = trim($data[3]);
                    $currentLebar = trim($data[4]);
                    continue; // Skip eksekusi ke bawah, langsung lanjut baca baris berikutnya
                }

                // 4. DETEKSI BARIS DATA ROLL
                // Ciri: Index 0 terisi No Roll yang karakternya panjang (> 5)
                if (strlen(trim($data[0])) > 5) {
                    
                    $no_roll      = trim($data[0]);
                    $no_roll_asli = trim($data[4] ?? '');
                    $sisa_kotor   = trim($data[5] ?? '0');
                    $no_po        = trim($data[6] ?? '');
                    
                    // Index 14 dan 15 menyesuaikan tumpukan titik koma dari hasil export sistem
                    $wilayah      = trim($data[14] ?? '');
                    $lokasi       = trim($data[15] ?? '');

                    // --- PERBAIKAN LOGIKA PARSING ANGKA DI SINI ---
                    
                    $sisa_bersih = trim($sisa_kotor);

                    // 1. HAPUS titik (.) karena di CSV lokal, titik adalah pemisah ribuan ("1.367" menjadi "1367")
                    $sisa_bersih = str_replace('.', '', $sisa_bersih);

                    // 2. UBAH koma (,) menjadi titik (.) karena PHP/SQL butuh titik untuk desimal ("1367,50" menjadi "1367.50")
                    $sisa_bersih = str_replace(',', '.', $sisa_bersih);

                    // 3. Pastikan hanya angka dan titik desimal yang tersisa
                    $sisa_bersih = preg_replace('/[^0-9.]/', '', $sisa_bersih);

                    // 4. Konversi ke Float dengan aman
                    $sisa_final  = (float) ($sisa_bersih ?: 0);

                    // 5. INSERT ATAU UPDATE KE DATABASE
                    // Jika no_roll sudah ada, update isinya. Jika belum ada, buat baru.
                    StockKertas::updateOrCreate(
                        ['no_roll' => $no_roll], 
                        [
                            'jenis'        => $currentJenis,
                            'gsm'          => $currentGsm,
                            'lebar'        => $currentLebar,
                            'no_roll_asli' => $no_roll_asli,
                            'sisa_kertas'  => $sisa_final,
                            'no_po'        => $no_po,
                            'wilayah'      => $wilayah,
                            'lokasi'       => $lokasi,
                            'updated_at'   => now()
                        ]
                    );
                }
            }

            // Tutup file dan simpan semua perubahan ke database
            fclose($handle);
            DB::commit();
            
            return back()->with('success', 'Database Stok Kertas berhasil disinkronisasi sepenuhnya!');

        } catch (\Exception $e) {
            // Batalkan semua perubahan jika ada yang error
            DB::rollBack();
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
            return back()->with('error', 'Gagal import! Pastikan format CSV sesuai. Error: ' . $e->getMessage());
        }
    }
}