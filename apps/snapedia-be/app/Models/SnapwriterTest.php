<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SnapwriterTest
 *
 * @property int $id
 * @property int|null $user_id
 * @property Carbon|null $taken_at
 * @property bool|null $passed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User|null $user
 *
 * @package App\Models
 */
class SnapwriterTest extends Model
{
	protected $table = 'snapwriter_tests';

	protected $casts = [
		'user_id' => 'int',
		'taken_at' => 'datetime',
		'passed' => 'bool'
	];

	protected $fillable = [
		'user_id',
		'taken_at',
		'passed'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
