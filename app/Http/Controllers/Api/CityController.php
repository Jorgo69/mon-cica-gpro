<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $country = $request->input('country', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $cities = City::search($query, $country ?: null, 10);

        return response()->json(
            $cities->map(fn ($c) => ['name' => $c->name, 'count' => $c->usage_count])
        );
    }
}
