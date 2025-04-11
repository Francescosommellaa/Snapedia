<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Article[] $articles
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Category extends Model
{
	protected $table = 'categories';

	protected $fillable = [
		'name',
		'slug'
	];

	public function articles()
	{
		return $this->hasMany(Article::class);
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'user_category_preferences')
					->withPivot('id')
					->withTimestamps();
	}
}
