<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** LIKE MODEL */
class Like extends Model {
  use HasFactory;

  public function user() {
      return $this->belongsTo(User::class);
  }

  public function article() {
      return $this->belongsTo(Article::class);
  }
}