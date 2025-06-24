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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedBodyAttribute()
    {
        return preg_replace_callback('/@([ぁ-んァ-ン一-龥a-zA-Z0-9\-_]+)/u', function ($matches) {
            $username = $matches[1];
            return '<span class="mention">@' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . '</span>';
        }, e($this->comment));
    }

}
