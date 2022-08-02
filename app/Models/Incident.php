<?php

namespace App\Models;

use App\Models\Car;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incident extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function cars()
    {
        return $this->belongsTo(Car::class);
    } 

    public function driver_in()
    {
        return $this->belongsTo(Driver::class, 'driver_in');
    } 
    public function driver_out()
    {
        return $this->belongsTo(Driver::class, 'driver_out');
    } 
}
