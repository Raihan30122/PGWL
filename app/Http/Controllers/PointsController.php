<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointsModel;
use Illuminate\Support\Facades\DB;

class PointsController extends Controller
{
    protected $points;

    public function __construct()
    {
        $this->points = new PointsModel();
    }

    public function index()
    {
        $points = PointsModel::all();
        $polylines = [];
        $polygons = []; // Tambahkan ini agar tidak error

        return view('map', [
            'title' => 'Map',
            'points' => $points,
            'polylines' => $polylines,
            'polygons' => $polygons // Pastikan ini dikirim ke view
        ]);
    }

    public function create()
    {
        return view('points.create', ['title' => 'Add Point']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:points,name',
            'description' => 'required',
            'geom_point' => 'required',
        ]);

        try {
            $geom = DB::raw("ST_GeomFromText('POINT({$request->geom_point})', 4326)");

            DB::table('points')->insert([
                'geom' => $geom,
                'name' => $request->name,
                'description' => $request->description,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('map')->with('success', 'Point has been successfully added.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add point: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $point = PointsModel::findOrFail($id);

        return view('points.show', [
            'title' => 'Point Detail',
            'point' => $point
        ]);
    }

    public function edit($id)
    {
        $point = PointsModel::findOrFail($id);

        return view('points.edit', [
            'title' => 'Edit Point',
            'point' => $point
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:points,name,' . $id,
            'description' => 'required',
            'geom_point' => 'required',
        ]);

        try {
            $geom = DB::raw("ST_GeomFromText('POINT({$request->geom_point})', 4326)");

            DB::table('points')->where('id', $id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'geom' => $geom,
                'updated_at' => now(),
            ]);

            return redirect()->route('map')->with('success', 'Point has been successfully updated.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update point: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            PointsModel::destroy($id);

            return redirect()->route('map')->with('success', 'Point has been successfully deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete point: ' . $e->getMessage());
        }
    }
}
