<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}