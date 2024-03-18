<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','pre_pregnancy_weight', 'current_weight', 'date_of_current_weight', 'last_menstrual_cycle'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
