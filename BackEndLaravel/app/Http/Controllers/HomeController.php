<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Log::info(
        //     $request->all()
        // );

        $query = Car::query()->orderBy('updated_at', 'desc');

        // Filter by brand
        if ($request->has('brand') && !empty($request->brand)) {
            $query->where('brand', 'LIKE', '%' . $request->brand . '%');
        }

        // Filter by model
        if ($request->has('model') && !empty($request->model)) {
            $query->where('model', 'LIKE', '%' . $request->model . '%');
        }

        // Filter by availability
        if ($request->has('availability') && !empty($request->availability)) {
            $query->where('availability', $request->availability);
        }

        // Pagination
        $cars = $query->paginate(8); // Adjust the number per page as needed

        // Log::info($cars);

        foreach ($cars as $car) {
            $car->photos = json_decode($car->photos); // Convert JSON string to array
        }


        return response()->json($cars);
    }

    public function detailCar($id)
    {
        $car = Car::with('user')->findOrFail($id);
        $car->photos = json_decode($car->photos); // Decode the photos JSON

        return response()->json([
            'id' => $car->id,
            'brand' => $car->brand,
            'model' => $car->model,
            'license_plate' => $car->license_plate,
            'daily_rent' => $car->daily_rent,
            'photos' => $car->photos,
            'user' => $car->user->name,
            'user_phone' => $car->user->phone,
            'availability' => $car->availability,
            'created_at' => $car->created_at,
            'updated_at' => $car->updated_at,
        ]);
    }
}
