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
 * @property Collection|Buyer[] $buyers
 * @property Collection|ContactLog[] $contact_logs
 * @property Collection|Publisher[] $publishers
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

	public function buyers()
	{
		return $this->belongsToMany(Buyer::class, 'buyer_contacts')
					->withPivot('id')
					->withTimestamps();
	}

	public function contact_logs()
	{
		return $this->hasMany(ContactLog::class);
	}

	public function publishers()
	{
		return $this->belongsToMany(Publisher::class, 'publisher_contacts')
					->withPivot('id')
					->withTimestamps();
	}
}
