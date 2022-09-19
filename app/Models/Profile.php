<?php

namespace App\Models;

use App\Models\User;
use App\Models\Department;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class)->orderBy('fullname','position','ecode');
    }
    public function company()
    {
        return $this->belongsTo(Company::class)->orderBy('title');
    } 
    public function department()
    {
        return $this->belongsTo(Department::class)->orderBy('title');
    }  
}
