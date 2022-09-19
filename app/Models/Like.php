<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function posts(){
        return $this->belongsToMany(Post::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
} 