<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Access;
use App\Models\Profile;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ]; 

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function access()
    {
        return $this->hasMany(Access::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

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
    
}
