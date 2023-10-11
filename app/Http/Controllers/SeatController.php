<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Trip;
use App\Models\Seat;

class SeatController extends Controller
{
    /**
     * Get the available seats for a given trip.
     *
     * @param Request $request
     * @param string $startCity
     * @param string $endCity
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableSeats(Request $request, $startCity, $endCity)
    {
        // Validate input
        $this->validateInput($request, [
            'startCity' => 'required|string|exists:cities,name',
            'endCity' => 'required|string|exists:cities,name|different:startCity',
        ]);

        // Get city IDs
        $startCityId = $this->getCityId($startCity);
        $endCityId = $this->getCityId($endCity);

        // Find trips that match the start and end cities
        $trips = Trip::where('start_city_id', $startCityId)
                     ->where('end_city_id', $endCityId)
                     ->get();

        // Calculate available seats for each trip
        $availableSeats = $this->calculateAvailableSeats($trips);

        return response()->json(['available_seats' => $availableSeats]);
    }

    /**
     * Book a seat for a given trip.
     *
     * @param Request $request
     * @param int $tripId
     * @param int $seatNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookSeat(Request $request, $tripId, $seatNumber)
    {
        // Validate input
        $request->validate([
            'tripId' => 'required|exists:trips,id',
            'seatNumber' => 'required|integer|min:1|max:12',
        ]);

        // Find the trip
        $trip = Trip::findOrFail($tripId);

        // Get already booked seats for the trip
        $bookedSeats = $trip->seats->pluck('seat_number')->toArray();

        // Check if the seat is already booked
        if ($this->isSeatBooked($seatNumber, $bookedSeats)) {
            return response()->json(['message' => 'Seat not available.'], 400);
        }

        // Book the seat
        Seat::create([
            'trip_id' => $tripId,
            'seat_number' => $seatNumber,
        ]);

        return response()->json(['message' => 'Seat booked successfully.']);
    }

    /**
     * Validate request input against given rules.
     *
     * @param Request $request
     * @param array $rules
     */

    /**
     * Get the ID of a city based on its name.
     *
     * @param string $cityName
     * @return int
     */
    private function getCityId($cityName)
    {
        return City::where('name', $cityName)->value('id');
    }

    /**
     * Calculate available seats for each trip.
     *
     * @param \Illuminate\Database\Eloquent\Collection $trips
     * @return array
     */
    private function calculateAvailableSeats($trips)
    {
        $availableSeats = [];

        foreach ($trips as $trip) {
            $bookedSeats = $trip->seats->pluck('seat_number')->toArray();
            $availableSeats[$trip->id] = array_diff(range(1, 12), $bookedSeats);
        }

        return $availableSeats;
    }

    /**
     * Check if a seat is already booked.
     *
     * @param int $seatNumber
     * @param array $bookedSeats
     * @return bool
     */
    private function isSeatBooked($seatNumber, $bookedSeats)
    {
        return in_array($seatNumber, $bookedSeats);
    }
}
