<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baby extends Model
{
    use HasFactory;
    protected $table = 'baby'; // Specify the correct table name

    protected $fillable = [
        'name', 'birthday', 'weight','height','head','user_id','gender'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
