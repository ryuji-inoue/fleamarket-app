<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'description',
        'price',
        'image_path',
        'condition_id',
        'status'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'item_category');
    }

    public function isFavoritedBy($user_id)
    {
        return $this->favorites()->where('user_id', $user_id)->exists();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites()->count();
    }
}