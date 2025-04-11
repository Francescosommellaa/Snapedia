<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Article
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $short_text
 * @property string|null $long_text
 * @property string|null $image_vertical
 * @property string|null $image_horizontal
 * @property bool|null $is_user_generated
 * @property int|null $user_id
 * @property int|null $category_id
 * @property int|null $likes_count
 * @property int|null $comments_count
 * @property int|null $saves_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User|null $user
 * @property Category|null $category
 * @property Collection|Comment[] $comments
 * @property Collection|Like[] $likes
 * @property Collection|Save[] $saves
 *
 * @package App\Models
 */
class Article extends Model
{
	protected $table = 'articles';

	protected $casts = [
		'is_user_generated' => 'bool',
		'user_id' => 'int',
		'category_id' => 'int',
		'likes_count' => 'int',
		'comments_count' => 'int',
		'saves_count' => 'int'
	];

	protected $fillable = [
		'title',
		'slug',
		'short_text',
		'long_text',
		'image_vertical',
		'image_horizontal',
		'is_user_generated',
		'user_id',
		'category_id',
		'likes_count',
		'comments_count',
		'saves_count'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function likes()
	{
		return $this->hasMany(Like::class);
	}

	public function saves()
	{
		return $this->hasMany(Save::class);
	}
}
