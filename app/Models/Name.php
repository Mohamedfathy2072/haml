<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Name extends Model
{
    use HasFactory;
    protected $fillable = ['gender', 'name', 'desc'];
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorite_name_user', 'name_id', 'user_id')->withTimestamps();
    }
}
