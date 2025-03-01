<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CityResource;
use App\Models\City;
use Inertia\Inertia;

class CityController extends Controller
{
	public function getCitiesByProvinceId($id)
	{
		$cities = City::where('province_id', $id)->get();

		return new CityResource('SUCCESS', 200, 'List data kota berdasarkan provinsi', $cities);
	}
}
