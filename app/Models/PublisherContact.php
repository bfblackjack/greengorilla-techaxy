<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PublisherContact
 * 
 * @property int $id
 * @property int $publisher_id
 * @property int $contact_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Contact $contact
 * @property Publisher $publisher
 *
 * @package App\Models
 */
class PublisherContact extends Model
{
	protected $table = 'publisher_contacts';

	protected $casts = [
		'publisher_id' => 'int',
		'contact_id' => 'int'
	];

	protected $fillable = [
		'publisher_id',
		'contact_id'
	];

	public function contact()
	{
		return $this->belongsTo(Contact::class);
	}

	public function publisher()
	{
		return $this->belongsTo(Publisher::class);
	}
}
