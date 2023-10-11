<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\City;
use App\Models\Trip;
use App\Models\Seat;

class SeatControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting available seats for a trip.
     *
     * @return void
     */
    public function testGetAvailableSeats()
    {
        // Arrange
    $trip = Trip::factory()->create();
    Seat::factory()->create(['trip_id' => $trip->id, 'seat_number' => 5]);

        $trip = Trip::factory()->create([
            'start_city_id' => $startCity->id,
            'end_city_id' => $endCity->id,
        ]);

        Seat::factory()->create([
            'trip_id' => $trip->id,
            'seat_number' => 5,
        ]);

        // Act
    $response = $this->post("/book-seat/{$trip->id}/5");

        // Assert
        $response->assertStatus(400)
            ->assertJson(['message' => 'Seat not available.']);

        $this->assertDatabaseMissing('seats', [
            'trip_id' => $trip->id,
            'seat_number' => 5,
        ]);
    }

    /**
     * Test booking a seat for a trip.
     *
     * @return void
     */
    public function testBookSeat()
    {
        // Arrange
        $trip = Trip::factory()->create();
        Seat::factory()->create(['trip_id' => $trip->id, 'seat_number' => 5]);

        // Act
        $response = $this->post("/book-seat/{$trip->id}/6");

        // Assert
        $response->assertStatus(200)
            ->assertJson(['message' => 'Seat booked successfully']);

        $this->assertDatabaseHas('seats', [
            'trip_id' => $trip->id,
            'seat_number' => 6,
        ]);
    }

    /**
     * Test booking an already booked seat.
     *
     * @return void
     */
    public function testBookAlreadyBookedSeat()
    {
        // Arrange
        $trip = Trip::factory()->create();
        Seat::factory()->create(['trip_id' => $trip->id, 'seat_number' => 5]);

        // Act
        $response = $this->post("/book-seat/{$trip->id}/5");

        // Assert
        $response->assertStatus(400)
            ->assertJson(['message' => 'Seat not available.']);

        $this->assertDatabaseMissing('seats', [
            'trip_id' => $trip->id,
            'seat_number' => 5,
        ]);
    }
}
