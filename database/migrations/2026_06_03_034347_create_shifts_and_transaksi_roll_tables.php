<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    // 1. Tabel untuk mencatat Shift yang sedang berjalan
    Schema::create('shifts', function (Blueprint $table) {
        $table->id();
        $table->string('kepala_shift');
        $table->integer('shift_ke'); // Shift 1, 2, atau 3
        $table->date('tanggal');
        $table->enum('status', ['aktif', 'selesai'])->default('aktif');
        $table->timestamps();
    });

    // 2. Tabel Log Transaksi Pengambilan & Pengembalian Roll
    Schema::create('transaksi_roll', function (Blueprint $table) {
        $table->id();
        $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
        $table->string('no_roll');
        $table->string('posisi_mesin');
        $table->dateTime('waktu_ambil');
        $table->dateTime('waktu_kembali')->nullable();
        $table->double('sisa_kilo_awal')->nullable(); // Diambil dari master stock awal
        $table->double('sisa_kilo_akhir')->nullable(); // Input saat roll balik
        $table->enum('status', ['diambil', 'kembali'])->default('diambil');
        $table->string('metode_input'); // 'scan' atau 'manual'
        $table->string('keterangan')->nullable(); // Catatan jika label rusak
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('transaksi_roll');
    Schema::dropIfExists('shifts');
}
};
