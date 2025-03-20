@extends('layouts.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <style>
        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }
    </style>
@endsection

@section('content')
<div id="map"></div>

<!-- Modal Create Point -->
<div class="modal fade" id="PointModal" tabindex="-1" aria-labelledby="PointModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('points.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Titik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="geom_point" name="geom_point">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Titik">
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Titik</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Create Polyline -->
<div class="modal fade" id="PolylineModal" tabindex="-1" aria-labelledby="PolylineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('polylines.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Garis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="geom_polyline" name="geom_polyline">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Garis">
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Garis</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Create Polygon -->
<div class="modal fade" id="PolygonModal" tabindex="-1" aria-labelledby="PolygonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('polygons.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Polygon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="geom_polygon" name="geom_polygon">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Polygon">
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Polygon</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://unpkg.com/@terraformer/wkt"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<script>
    var map = L.map('map').setView([-7.797068, 110.370529], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    var points = @json($points);
    var polylines = @json($polylines);
    var polygons = @json($polygons);

    // Show points
    points.forEach(function(point) {
        var wkt = Terraformer.WKT.parse(point.geom_point);
        var coords = [wkt.coordinates[1], wkt.coordinates[0]];
        L.marker(coords).addTo(map).bindPopup("<b>" + point.name + "</b><br>" + point.description);
    });

    // Show polylines
    polylines.forEach(function(line) {
        var wkt = Terraformer.WKT.parse(line.geom_polyline);
        var latlngs = wkt.coordinates.map(function(coord) { return [coord[1], coord[0]]; });
        L.polyline(latlngs, { color: 'blue' }).addTo(map).bindPopup("<b>" + line.name + "</b><br>" + line.description);
    });

    // Show polygons
    polygons.forEach(function(poly) {
        var wkt = Terraformer.WKT.parse(poly.geom_polygon);
        var latlngs = wkt.coordinates[0].map(function(coord) { return [coord[1], coord[0]]; });
        L.polygon(latlngs, { color: 'green' }).addTo(map).bindPopup("<b>" + poly.name + "</b><br>" + poly.description);
    });

    // Drawing
    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);
    var drawControl = new L.Control.Draw({
        draw: {
            polygon: true,
            polyline: true,
            rectangle: false,
            circle: false,
            marker: true
        },
        edit: { featureGroup: drawnItems }
    });
    map.addControl(drawControl);

    map.on('draw:created', function (e) {
        var type = e.layerType,
            layer = e.layer;
        var geojson = layer.toGeoJSON();
        var wkt = Terraformer.geojsonToWKT(geojson.geometry);

        if (type === 'marker') {
            $('#geom_point').val(wkt);
            $('#PointModal').modal('show');
        } else if (type === 'polyline') {
            $('#geom_polyline').val(wkt);
            $('#PolylineModal').modal('show');
        } else if (type === 'polygon') {
            $('#geom_polygon').val(wkt);
            $('#PolygonModal').modal('show');
        }

        drawnItems.addLayer(layer);
    });
</script>
@endsection
