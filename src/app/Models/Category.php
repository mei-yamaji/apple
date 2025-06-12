<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    public function boards()
    {
        return $this->hasMany(Board::class);
    }
}
