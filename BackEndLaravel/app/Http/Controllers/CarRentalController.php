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
        $numberOfDays = $endDate->diff($startDate)->days + 1; // Termasuk tanggal akhir
        $totalCost = $car->daily_rent * $numberOfDays;

        // Buat rekaman penyewaan
        $rental = Rental::create([
            'car_id' => $car->id,
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_cost' => $totalCost,       // Tambahkan total biaya
            'number_of_days' => $numberOfDays // Tambahkan jumlah hari
        ]);

        return response()->json($rental, 201);
    }


    public function rentalHistory()
    {
        $rentals = Rental::with('car')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($rentals);
    }

    public function availableDates($id)
    {
        // Get the car by ID
        $car = Car::findOrFail($id);

        // Retrieve all rentals for this car
        $rentals = Rental::where('car_id', $car->id)
            ->get(['start_date', 'end_date']);

        return response()->json($rentals);
    }

    public function completeRental($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->status = 'completed'; // Update status to 'completed'
        $rental->save();

        return response()->json(['message' => 'Rental completed successfully.']);
    }
}
