<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class image extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function posts()
    { 
        return $this->morphedByMany(
            Post::class,
            'imageable',
            'imageables',
            'image_id',
            'imageable_id',
            'id', 
        );
    }

    public function users()
    { 
        return $this->morphedByMany(
            User::class,
            'imageable',
            'imageables',
            'image_id',
            'imageable_id',
            'id', 
        );
    }

    public function uploaded_by()
    {
        return $this->belongsTo(User::class);
    }
}
