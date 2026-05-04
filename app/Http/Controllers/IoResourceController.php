<?php

namespace App\Http\Controllers;

use App\Services\WilayahService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class IoResourceController extends Controller
{
    protected $service;
    protected $viewPrefix;
    protected $itemVariable = 'item';

    public function index()
    {
        $params = request()->query();
        return view("{$this->viewPrefix}.index")->with('params', http_build_query($params));
    }

    public function search(Request $request)
    {
        $items = $this->service->search($request->all());
        if ($request->has('ajax')) return $items;
        return view("{$this->viewPrefix}._table", [Str::plural($this->itemVariable) => $items]);
    }

    public function create()
    {
        return view("{$this->viewPrefix}._form");
    }

    public function store(Request $request)
    {
        return $this->service->store($request->all());
    }

    public function edit($id)
    {
        $item = $this->service->find($id);
        return view("{$this->viewPrefix}._form", [$this->itemVariable => $item]);
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function update(Request $request, $id)
    {
        return $this->service->update($request->all(), $id);
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function restore($id)
    {
        return $this->service->restore($id);
    }

    public function getRandomlatitudeLongitude(Request $request)
    {
        $wilayahService = new WilayahService();
        $wilayah = $wilayahService->find($request->input('wilayah_id'));

        $path = public_path($wilayah->polygon);
        if (!file_exists($path)) {
            return response()->json(['error' => 'GeoJSON file not found'], 404);
        }

        $geojsonContent = json_decode(file_get_contents($path), true);
        $multiPolygon = $geojsonContent['features'][0]['geometry']['coordinates'];
        $minLng = $maxLng = $minLat = $maxLat = null;
        foreach ($multiPolygon as $polygons) {
            foreach ($polygons as $ring) {
                foreach ($ring as $coord) {
                    $lng = $coord[0];
                    $lat = $coord[1];

                    if (is_null($minLng) || $lng < $minLng) $minLng = $lng;
                    if (is_null($maxLng) || $lng > $maxLng) $maxLng = $lng;
                    if (is_null($minLat) || $lat < $minLat) $minLat = $lat;
                    if (is_null($maxLat) || $lat > $maxLat) $maxLat = $lat;
                }
            }
        }

        $found = false;
        $randomLat = $randomLng = null;
        $maxAttempts = 100;
        $attempts = 0;

        while (!$found && $attempts < $maxAttempts) {
            $attempts++;
            $randomLng = $minLng + mt_rand() / mt_getrandmax() * ($maxLng - $minLng);
            $randomLat = $minLat + mt_rand() / mt_getrandmax() * ($maxLat - $minLat);

            if ($this->isPointInMultiPolygon($randomLng, $randomLat, $multiPolygon)) {
                $found = true;
            }
        }

        return $request->merge(['latitude' => $randomLat, 'longitude' => $randomLng]);
    }

    private function isPointInMultiPolygon($lng, $lat, $multiPolygon)
    {
        foreach ($multiPolygon as $polygons) {
            foreach ($polygons as $ring) {
                $inside = false;
                $n = count($ring);
                for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
                    $xi = $ring[$i][0]; $yi = $ring[$i][1];
                    $xj = $ring[$j][0]; $yj = $ring[$j][1];

                    $intersect = (($yi > $lat) != ($yj > $lat)) && ($lng < ($xj - $xi) * ($lat - $yi) / ($yj - $yi) + $xi);
                    if ($intersect) $inside = !$inside;
                }
                if ($inside) return true;
            }
        }
        return false;
    }
}
