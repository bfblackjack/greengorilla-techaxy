<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserInvite
 * 
 * @property int $id
 * @property int $user_id
 * @property Carbon $expire_dt
 * @property string $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class UserInvite extends Model
{
	protected $table = 'user_invites';

	protected $casts = [
		'user_id' => 'int',
		'expire_dt' => 'datetime'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'user_id',
		'expire_dt',
		'token'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
