<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\PolygonsModel;
use Illuminate\Http\Request;

class PolygonsController extends Controller
{
    protected $polygons;

    // Inisialisasi model
    public function __construct()
    {
        $this->polygons = new PolygonsModel(); // Model polygon
    }

    /**
     * Tampilkan halaman peta (jika ingin tampilkan data polygon, uncomment bagian polygons)
     */
    public function index()
    {
        $data = [
            'title' => 'Map Polygon',
            // 'polygons' => $this->polygons->all(), // Uncomment jika mau tampilkan data polygon ke view
        ];

        return view('map', $data); // Sesuaikan nama view jika perlu
    }

    /**
     * Simpan polygon baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate(
            [
                'name' => 'required|unique:polygons,name',
                'description' => 'required',
                'geom' => 'required', // Menggunakan 'geom' sesuai kolom di database
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom.required' => 'Geometry polygon is required',
            ]
        );

        // Gunakan query manual untuk menyimpan geometry
        $insert = DB::insert("INSERT INTO polygons (geom, name, description, created_at, updated_at) VALUES (ST_GeomFromText(?, 4326), ?, ?, NOW(), NOW())", [
            $request->geom, // Menggunakan geom agar sesuai dengan database
            $request->name,
            $request->description
        ]);

        // Cek apakah berhasil disimpan
        if (!$insert) {
            return redirect()->route('map')->with('error', 'Polygon failed to add');
        }

        return redirect()->route('map')->with('success', 'Polygon has been added');
    }
}
