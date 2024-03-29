<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'text',
        'status',
        'tag',
        'coords',
        'created_at',
    ];

    public function token()
    {
        return $this->belongsTo(Token::class);
    }
}
