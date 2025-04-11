<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PremiumSubscription
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $type
 * @property string|null $plan
 * @property Carbon|null $started_at
 * @property int|null $renewed_count
 * @property bool|null $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User|null $user
 * @property Collection|SubscriptionCancellation[] $subscription_cancellations
 *
 * @package App\Models
 */
class PremiumSubscription extends Model
{
	protected $table = 'premium_subscriptions';

	protected $casts = [
		'user_id' => 'int',
		'started_at' => 'datetime',
		'renewed_count' => 'int',
		'active' => 'bool'
	];

	protected $fillable = [
		'user_id',
		'type',
		'plan',
		'started_at',
		'renewed_count',
		'active'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function subscription_cancellations()
	{
		return $this->hasMany(SubscriptionCancellation::class, 'subscription_id');
	}
}
