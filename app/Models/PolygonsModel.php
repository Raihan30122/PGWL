<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolygonsModel extends Model
{
    // Nama tabel yang digunakan
    protected $table = 'polygons';

    // Kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'geom',
        'name',
        'description',
    ];

    // Jika menggunakan created_at dan updated_at (timestamps), set true
    public $timestamps = true;

    // Jika butuh meng-cast geom (opsional tergantung kebutuhan, contoh JSON jika perlu)
    // protected $casts = [
    //     'geom' => 'string', // atau 'json' jika butuh diolah sebagai JSON
    // ];
}
