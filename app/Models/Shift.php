<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';
    // protected $fillable = ['kepala_shift', 'tanggal', 'status'];
    protected $guarded = ['id']; // Melindungi kolom id agar tidak bisa diisi massal

    public function transaksi() {
        return $this->hasMany(TransaksiRoll::class, 'shift_id');
    }
}
