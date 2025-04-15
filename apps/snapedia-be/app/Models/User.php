<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;


/** USER MODEL */
class User extends Authenticatable {

    protected $fillable = [
        'name',
        'surname',
        'username',
        'email',
        'password',
        'age',
        'type',
        'premium_tier_id',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    use HasApiTokens, HasFactory, Notifiable;

    public function premiumTier() {
        return $this->belongsTo(PremiumTier::class);
    }

    public function articles() {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function saves() {
        return $this->hasMany(Save::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function followers() {
        return $this->hasMany(Follower::class, 'user_id');
    }

    public function following() {
        return $this->hasMany(Follower::class, 'follower_id');
    }

    public function writerTest() {
        return $this->hasOne(WriterTest::class);
    }

    public function updateLogs() {
        return $this->hasMany(UserUpdateLog::class);
    }
}
