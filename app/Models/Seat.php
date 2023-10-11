<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;
    protected $fillable = ['trip_id', 'seat_number','bus_id'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
