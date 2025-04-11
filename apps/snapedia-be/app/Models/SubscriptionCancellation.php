<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SubscriptionCancellation
 *
 * @property int $id
 * @property int|null $subscription_id
 * @property Carbon|null $cancelled_at
 * @property float|null $refund_amount
 * @property string|null $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property PremiumSubscription|null $premium_subscription
 *
 * @package App\Models
 */
class SubscriptionCancellation extends Model
{
	protected $table = 'subscription_cancellations';

	protected $casts = [
		'subscription_id' => 'int',
		'cancelled_at' => 'datetime',
		'refund_amount' => 'float'
	];

	protected $fillable = [
		'subscription_id',
		'cancelled_at',
		'refund_amount',
		'reason'
	];

	public function premium_subscription()
	{
		return $this->belongsTo(PremiumSubscription::class, 'subscription_id');
	}
}
