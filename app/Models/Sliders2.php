<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sliders2 extends Model
{
    use HasFactory;
    protected $table = 'sliders2'; // Specify the correct table name

    protected $fillable = [
        'title',
        'slider_path', // Make sure this field is included here
    ];
}
