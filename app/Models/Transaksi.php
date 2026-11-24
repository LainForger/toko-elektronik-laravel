<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'no_nota',
        'pelanggan_id',
        'total_harga',
        'bayar',
        'kembalian',
    ];

    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
