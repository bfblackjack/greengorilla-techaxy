<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @property int $id
 * @property string $first
 * @property string $last
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Buyer $buyer
 * @property Collection|ContactLog[] $contact_logs
 * @property Publisher $publisher
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasRoles, HasPermissions, HasApiTokens, Notifiable;

	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime',
		'two_factor_confirmed_at' => 'datetime',
		'current_team_id' => 'int'
	];

	protected $hidden = [
		'password',
		'two_factor_secret',
		'remember_token'
	];

	protected $fillable = [
		'first',
		'last',
		'email',
		'email_verified_at',
		'password',
		'two_factor_secret',
		'two_factor_recovery_codes',
		'two_factor_confirmed_at',
		'remember_token',
		'current_team_id',
		'profile_photo_path'
	];

	public function buyer()
	{
		return $this->hasOne(Buyer::class);
	}

	public function contact_logs()
	{
		return $this->hasMany(ContactLog::class, 'author_id');
	}

	public function publisher()
	{
		return $this->hasOne(Publisher::class);
	}
}
