<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $emailPrimary
 * @property string|null $emailSecondary
 * @property string|null $phone
 * @property int|null $age
 * @property string|null $profile_image
 * @property string|null $password
 * @property string|null $two_fa_secret
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Article[] $articles
 * @property Collection|Comment[] $comments
 * @property Collection|Like[] $likes
 * @property Collection|Save[] $saves
 * @property Collection|Category[] $categories
 * @property Collection|PremiumSubscription[] $premium_subscriptions
 * @property Collection|SnapwriterTest[] $snapwriter_tests
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';

	protected $casts = [
		'age' => 'int'
	];

	protected $hidden = [
		'password',
		'two_fa_secret'
	];

	protected $fillable = [
		'name',
		'surname',
		'emailPrimary',
		'emailSecondary',
		'phone',
		'age',
		'profile_image',
		'password',
		'two_fa_secret'
	];

	public function articles()
	{
		return $this->hasMany(Article::class);
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

	public function categories()
	{
		return $this->belongsToMany(Category::class, 'user_category_preferences')
					->withPivot('id')
					->withTimestamps();
	}

	public function premium_subscriptions()
	{
		return $this->hasMany(PremiumSubscription::class);
	}

	public function snapwriter_tests()
	{
		return $this->hasMany(SnapwriterTest::class);
	}
}
