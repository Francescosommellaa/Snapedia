<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserCategoryPreference
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $category_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User|null $user
 * @property Category|null $category
 *
 * @package App\Models
 */
class UserCategoryPreference extends Model
{
	protected $table = 'user_category_preferences';

	protected $casts = [
		'user_id' => 'int',
		'category_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'category_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}
}
