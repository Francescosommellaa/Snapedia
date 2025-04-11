<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** COMMENT MODEL */
class Comment extends Model {
  use HasFactory;

  public function user() {
      return $this->belongsTo(User::class);
  }

  public function article() {
      return $this->belongsTo(Article::class);
  }

  public function parent() {
      return $this->belongsTo(Comment::class, 'parent_id');
  }

  public function replies() {
      return $this->hasMany(Comment::class, 'parent_id');
  }
}