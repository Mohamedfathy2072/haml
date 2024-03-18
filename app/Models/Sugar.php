<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sugar extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','measuring_sugar', 'date_of_measurement'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
