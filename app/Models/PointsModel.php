<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointsModel extends Model
{
    // Hubungkan ke tabel 'points'
    protected $table = 'points';

    // Kolom yang boleh diisi
    protected $fillable = ['geom', 'name', 'description'];
}
