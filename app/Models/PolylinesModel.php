<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolylinesModel extends Model
{
    // Hubungkan ke tabel 'polylines'
    protected $table = 'polylines';

    // Kolom yang boleh diisi
    protected $fillable = ['geom', 'name', 'description'];

    // Jika kamu menggunakan tipe geometry, dan tidak perlu timestamps, bisa di-nonaktifkan
    public $timestamps = true; // Ubah ke false jika tidak pakai created_at & updated_at
}
