<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\PolylinesModel;
use Illuminate\Http\Request;

class PolylinesController extends Controller
{
    protected $polylines;

    // Inisialisasi model
    public function __construct()
    {
        $this->polylines = new PolylinesModel();
    }

    /**
     * Tampilkan halaman peta (jika ingin tampilkan data, tinggal buka komentar polylines)
     */
    public function index()
    {
        $data = [
            'title' => 'Map Polyline',
            // 'polylines' => $this->polylines->all(), // Uncomment jika ingin kirim data polyline ke view
        ];

        return view('map', $data);
    }

    /**
     * Simpan polyline baru ke database
     */
    public function store(Request $request)
    {
        // Validasi inputan
        $request->validate(
            [
                'name' => 'required|unique:polylines,name',
                'description' => 'required',
                'geom' => 'required', // Sesuai dengan kolom di database
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom.required' => 'Geometry polyline is required',
            ]
        );

        // Gunakan query manual agar ST_GeomFromText bisa diproses
        $insert = DB::insert("INSERT INTO polylines (geom, name, description, created_at, updated_at) VALUES (ST_GeomFromText(?, 4326), ?, ?, NOW(), NOW())", [
            $request->geom, // Menggunakan `geom` sesuai dengan kolom di database
            $request->name,
            $request->description
        ]);

        // Cek apakah berhasil disimpan
        if (!$insert) {
            return redirect()->route('map')->with('error', 'Polyline failed to add');
        }

        return redirect()->route('map')->with('success', 'Polyline has been added');
    }
}
