<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabyKick extends Model
{
    use HasFactory;
    protected $table = 'baby_kicks'; // Specify the correct table name

    protected $fillable = [
        'user_id', 'number_of_kicks', 'start_hour','end_hour'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
