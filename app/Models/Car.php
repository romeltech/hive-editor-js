<?php

namespace App\Models;

use App\Models\Driver;
use App\Models\Incident;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    } 

    public function drivers()
    {
        return $this->belongsToMany(Driver::class);
    }  
    
}
