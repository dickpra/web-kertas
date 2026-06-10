<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kalkulasi_spks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sesi'); // Contoh: KALK-20260608-001
            $table->json('data_spk');    // Ini rahasianya! Menyimpan banyak SPK dalam 1 kolom
            $table->float('total_aktual_semua')->default(0); // Buat rekapan cepat di tabel luar
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kalkulasi_spks');
    }
};