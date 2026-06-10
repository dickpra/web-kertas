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
        Schema::create('stock_kertas', function (Blueprint $table) {
            $table->id();
            $table->string('jenis')->nullable();
            $table->string('gsm')->nullable();
            $table->string('lebar')->nullable();
            
            // no_roll dibuat unique agar saat import CSV tidak ada duplikat 
            // dan fungsi updateOrCreate() bisa berjalan lancar
            $table->string('no_roll')->unique(); 
            
            $table->string('no_roll_asli')->nullable();
            
            // Pakai float atau decimal agar bisa menyimpan sisa kilo yang berkoma
            $table->float('sisa_kertas')->default(0); 
            
            $table->string('no_po')->nullable();
            $table->string('wilayah')->nullable();
            $table->string('lokasi')->nullable();
            
            $table->timestamps(); // otomatis membuat kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_kertas');
    }
};