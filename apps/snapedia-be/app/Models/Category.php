<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** CATEGORY MODEL */
class Category extends Model {
  use HasFactory;

  public function articles() {
      return $this->belongsToMany(Article::class, 'article_category');
  }
}