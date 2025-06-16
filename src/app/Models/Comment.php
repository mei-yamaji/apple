<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['board_id', 'user_id', 'comment'];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
