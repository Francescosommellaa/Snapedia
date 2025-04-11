<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** ARTICLE MODEL */
class Article extends Model {
    use HasFactory;

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'article_category');
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
}
