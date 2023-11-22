<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Contact
 *
 * @property int $id
 * @property string $internal_id
 * @property string|null $external_id
 * @property string $caller_id
 * @property array|null $additional_attributes
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Buyer $buyer
 * @property Collection|ContactLog[] $contact_logs
 * @property Publisher $publisher
 *
 * @package App\Models
 */
class Contact extends Model
{
	protected $table = 'contacts';

	protected $casts = [
		'additional_attributes' => 'json'
	];

	protected $fillable = [
		'internal_id',
		'external_id',
		'caller_id',
		'additional_attributes',
		'status'
	];

	public function buyer()
	{
		return $this->belongsTo(BuyerContact::class, 'id', 'contact_id');
	}

	public function contact_logs()
	{
		return $this->hasMany(ContactLog::class);
	}

	public function publisher()
	{
		return $this->belongsTo(PublisherContact::class, 'id', 'contact_id');
	}
}
