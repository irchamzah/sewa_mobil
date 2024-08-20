<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Car::where('user_id', $userId)->orderByDesc('updated_at');

        if ($request->has('brand') && !empty($request->brand)) {
            $query->where('brand', 'LIKE', '%' . $request->brand . '%');
        }

        if ($request->has('model') && !empty($request->model)) {
            $query->where('model', 'LIKE', '%' . $request->model . '%');
        }

        if ($request->has('availability') && !empty($request->availability)) {
            $query->where('availability', $request->availability);
        }

        $cars = $query->paginate(8);

        foreach ($cars as $car) {
            $car->photos = json_decode($car->photos);
        }

        return response()->json($cars);
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string',
            'model' => 'required|string',
            'licensePlate' => 'required|string',
            'dailyRent' => 'required|numeric',
            'availability' => 'required|string|in:available,unavailable',
            'photos.*' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        $car = new Car();
        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->license_plate = $request->licensePlate;
        $car->daily_rent = $request->dailyRent;
        $car->availability = $request->availability;
        $car->user_id = Auth::id();
        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('cars', 'public');
                $photos[] = $path;
            }
            $car->photos = json_encode($photos);
        }
        $car->save();

        return response()->json($car, 201);
    }

    public function show($id)
    {
        $car = Car::findOrFail($id);
        $car->photos = json_decode($car->photos, true);

        return response()->json($car);
    }


    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $request->validate([
            'brand' => 'required|string',
            'model' => 'required|string',
            'licensePlate' => 'required|string',
            'dailyRent' => 'required|numeric',
            'availability' => 'required|string|in:available,unavailable',
        ]);

        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->license_plate = $request->licensePlate;
        $car->daily_rent = $request->dailyRent;
        $car->availability = $request->availability;
        if ($request->hasFile('photos')) {
            $oldPhotos = json_decode($car->photos);
            if (is_array($oldPhotos)) {
                foreach ($oldPhotos as $oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('cars', 'public');
                $photos[] = $path;
            }
            $car->photos = json_encode($photos);
        }
        $car->save();

        return response()->json($car);
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        if ($car->photos) {
            $photos = json_decode($car->photos);
            foreach ($photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }
        $car->delete();

        return response()->json(null, 204);
    }
}
