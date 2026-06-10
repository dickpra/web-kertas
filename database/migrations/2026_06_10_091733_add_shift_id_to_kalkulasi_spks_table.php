<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('kalkulasi_spks', function (Blueprint $table) {
            $table->unsignedBigInteger('shift_id')->nullable()->after('total_aktual_semua');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kalkulasi_spks', function (Blueprint $table) {
            //
        });
    }
};
