<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class like extends Model
{
    use HasFactory;

    protected $table = 'like'; 
    
    protected $fillable = [
        'user_id',
        'liked_user_id',
        'tweet_id',
    ] ;

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likedUser(){
        return $this->belongsTo(User::class, 'liked_user_id');
    }
}
