<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excerpt extends Model
{
    use HasFactory;
    protected $table = 'excerpts';

    protected $fillable = [
        'title', 'hint', 'description'
    ];
    public function images()
    {
        return $this->hasMany(Images::class);
    }
}
