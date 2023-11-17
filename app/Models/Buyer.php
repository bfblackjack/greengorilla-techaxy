<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Buyer
 * 
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property Collection|Contact[] $contacts
 *
 * @package App\Models
 */
class Buyer extends Model
{
	use SoftDeletes;
	protected $table = 'buyers';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'name'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function contacts()
	{
		return $this->belongsToMany(Contact::class, 'buyer_contacts')
					->withPivot('id')
					->withTimestamps();
	}
}
