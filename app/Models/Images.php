<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    use HasFactory;
    protected $fillable = ['excerpt_id', 'image_path'];
    public function excerpt()
    {
        return $this->belongsTo(Excerpt::class);
    }
}
