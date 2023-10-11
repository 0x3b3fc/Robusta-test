<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeatController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// route to get available seat
Route::get('/seats/{startCity}/{endCity}', [SeatController::class, 'getAvailableSeats']);
// route to store and book seat in database
Route::post('/book-seat/{tripId}/{seatNumber}', [SeatController::class, 'bookSeat']);
