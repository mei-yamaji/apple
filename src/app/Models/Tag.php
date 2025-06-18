<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function boards()
    {
        return $this->belongsToMany(Board::class, 'board_tag', 'tag_id', 'board_id');
    }

}
