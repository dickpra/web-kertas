<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockKertas extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional tapi disarankan)
    protected $table = 'stock_kertas';

    protected $guarded = [];

    // Matikan timestamps karena tabel dari scraper Python kita tidak punya kolom created_at & updated_at
    public $timestamps = false; 
}