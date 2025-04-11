<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** FOLLOWER MODEL */
class Follower extends Model {
  use HasFactory;

  public function user() {
      return $this->belongsTo(User::class, 'user_id');
  }

  public function follower() {
      return $this->belongsTo(User::class, 'follower_id');
  }
}