<?php

namespace App\Models;

use App\Models\Like;
use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Category;
use App\Models\PollAnswer;
use App\Models\PollChoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function images()
    { 
        return $this->morphToMany(
            Image::class,
            'imageable',
            'imageables',
            'imageable_id',
            'image_id',
            '',
            'id'
        );
    }

    public function categories()
    { 
        return $this->morphToMany(
            Category::class,
            'categoriable',
            'categoriables',
            'categoriable_id',
            'category_id',
            '',
            'id'
        );
    }

    public function comments()
    { 
        return $this->hasMany(Comment::class)->orderBy('id','desc');
    }

    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function events(){
        return $this->hasOne(Post_event::class);
    }

    public function poll_choices(){
        return $this->hasMany(PollChoice::class);
    }

    public function poll_answer(){
        return $this->hasMany(PollAnswer::class)->orderBy('id','desc');
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }
}
