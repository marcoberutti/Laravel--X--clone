<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweets extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Campo per la chiave esterna
        'user_name',
        'tweetTitle', // Titolo del tweet
        'tweetContent', // Contenuto del tweet
        'image'
    ];

    // Relazione con il modello User
    public function user()
    {
        return $this->belongsTo(User::class);

    }
}
