<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description','publish_date','category_id'];
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeLatestBoards($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopePopularBoards($query)
    {
        return $query->orderBy('likes_count', 'desc');
    }

    public function scopeMostViewedBoards($query)
    {
        return $query->orderBy('view_count', 'desc');
    }
}
