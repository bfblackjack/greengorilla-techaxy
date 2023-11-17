<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BuyerContact
 * 
 * @property int $id
 * @property int $buyer_id
 * @property int $contact_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Buyer $buyer
 * @property Contact $contact
 *
 * @package App\Models
 */
class BuyerContact extends Model
{
	protected $table = 'buyer_contacts';

	protected $casts = [
		'buyer_id' => 'int',
		'contact_id' => 'int'
	];

	protected $fillable = [
		'buyer_id',
		'contact_id'
	];

	public function buyer()
	{
		return $this->belongsTo(Buyer::class);
	}

	public function contact()
	{
		return $this->belongsTo(Contact::class);
	}
}
