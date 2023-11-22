<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BuyerEndpoint
 * 
 * @property int $id
 * @property int $buyer_id
 * @property string $endpoint_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Buyer $buyer
 *
 * @package App\Models
 */
class BuyerEndpoint extends Model
{
	protected $table = 'buyer_endpoints';

	protected $casts = [
		'buyer_id' => 'int'
	];

	protected $fillable = [
		'buyer_id',
		'endpoint_url'
	];

	public function buyer()
	{
		return $this->belongsTo(Buyer::class);
	}
}
