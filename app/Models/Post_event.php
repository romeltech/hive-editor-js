<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post_event extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function posts(){
        return $this->belongsTo(Post::class);
    }
}
