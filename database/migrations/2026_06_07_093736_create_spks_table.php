<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('spks', function (Blueprint $table) {
            $table->id();
            $table->string('no_spk');
            $table->float('lebar_cm');
            $table->float('panjang_m');
            
            // Faktor Gelombang
            $table->float('faktor_bm')->default(1.36);
            $table->float('faktor_cm')->default(1.46);
            
            // Input GSM
            $table->float('gsm_db')->nullable();
            $table->float('gsm_bm')->nullable();
            $table->float('gsm_bl')->nullable();
            $table->float('gsm_cm')->nullable();
            $table->float('gsm_cl')->nullable();
            
            // Hasil Kalkulasi Kg
            $table->float('kg_db')->default(0);
            $table->float('kg_bm')->default(0);
            $table->float('kg_bl')->default(0);
            $table->float('kg_cm')->default(0);
            $table->float('kg_cl')->default(0);
            
            $table->float('total_kg')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spks');
    }
};