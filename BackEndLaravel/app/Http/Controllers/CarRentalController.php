<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarRentalController extends Controller
{
    public function rentCar(Request $request)
    {
        // Validasi input
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $car = Car::findOrFail($request->car_id);

        // Periksa ketersediaan mobil pada tanggal yang diminta
        $isAvailable = Rental::where('car_id', $request->car_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhereRaw('? BETWEEN start_date AND end_date', [$request->start_date])
                    ->orWhereRaw('? BETWEEN start_date AND end_date', [$request->end_date]);
            })
            ->doesntExist();

        if (!$isAvailable) {
            return response()->json(['message' => 'Car is not available on the selected dates'], 422);
        }

        // Hitung jumlah hari dan total biaya
        $startDate = new \DateTime($request->start_date);
        $endDate = new \DateTime($request->end_date);
        $numberOfDays = $endDate->diff($startDate)->days + 1;
        $totalCost = $car->daily_rent * $numberOfDays;

        // Buat baris baru di tabel 'rentals'
        $rental = Rental::create([
            'car_id' => $car->id,
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_cost' => $totalCost,
            'number_of_days' => $numberOfDays
        ]);

        return response()->json($rental, 201);
    }


    public function rentalHistory()
    {
        // Tentukan jumlah item per halaman
        $perPage = 4;

        $rentals = Rental::with('car')
            ->where('user_id', Auth::id())
            ->orderBy('end_date', 'desc')
            ->paginate($perPage);

        foreach ($rentals as $rental) {
            if ($rental->car) {
                if (is_string($rental->car->photos)) {
                    $rental->car->photos = json_decode($rental->car->photos, true);
                }
            }
        }

        return response()->json($rentals);
    }


    public function unAvailableDates($id)
    {
        $car = Car::findOrFail($id);

        $rentals = Rental::where('car_id', $car->id)
            ->get(['start_date', 'end_date']);

        return response()->json($rentals);
    }

    public function completeRental($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->status = 'completed';
        $rental->save();

        return response()->json(['message' => 'Rental completed successfully.']);
    }
}
