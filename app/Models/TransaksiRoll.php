<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiRoll extends Model
{
    use HasFactory;

    protected $table = 'transaksi_roll';
    // protected $fillable = ['shift_id', 'no_roll', 'waktu_ambil', 'waktu_kembali', 'sisa_kilo_awal', 'sisa_kilo_akhir', 'status', 'metode_input', 'keterangan'];
    protected $guarded = [];


    public function masterKertas() {
        return $this->belongsTo(StockKertas::class, 'no_roll', 'no_roll');
    }

    public function shift() {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
