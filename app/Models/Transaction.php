<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi
    protected $fillable = ['product_id', 'quantity', 'total_price'];

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
