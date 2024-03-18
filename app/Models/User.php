<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'fcm_token',
        'period'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
    public function favoriteNames()
    {
        return $this->belongsToMany(Name::class, 'favorite_name_user', 'user_id', 'name_id')->withTimestamps();
    }
    public function babyKicks()
    {
        return $this->hasMany(BabyKick::class);
    }
    public function weights()
    {
        return $this->hasMany(Weight::class);
    }
    public function sugars()
    {
        return $this->hasMany(Sugar::class);
    }
    public function pressures()
    {
        return $this->hasMany(Pressure::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    }
