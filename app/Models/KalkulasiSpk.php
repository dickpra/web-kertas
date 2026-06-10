<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KalkulasiSpk extends Model
{
    use HasFactory;

    // protected $fillable = ['kode_sesi', 'data_spk', 'total_aktual_semua'];
    protected $guarded = [];

    // Rahasia agar JSON langsung bisa dilooping di Blade (View)
    protected $casts = [
        'data_spk' => 'array', 
    ];
}