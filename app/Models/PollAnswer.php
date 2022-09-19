<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use App\Models\Profile;
use App\Models\PollChoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PollAnswer extends Model
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;
    protected $guarded = [];

    public function posts(){
        return $this->belongsTo(Post::class);
    }

    public function choices(){
        return $this->belongsTo(PollChoice::class, 'poll_choice_id');
    }

    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'user_id');
    }
}
