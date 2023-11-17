<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SettingsLogAction
 * 
 * @property int $id
 * @property string $action
 * 
 * @property Collection|ContactLog[] $contact_logs
 *
 * @package App\Models
 */
class SettingsLogAction extends Model
{
	protected $table = 'settings_log_actions';
	public $timestamps = false;

	protected $fillable = [
		'action'
	];

	public function contact_logs()
	{
		return $this->hasMany(ContactLog::class, 'action_id');
	}
}
