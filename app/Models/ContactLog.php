<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ContactLog
 * 
 * @property int $id
 * @property int $contact_id
 * @property int $action_id
 * @property int $author_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property SettingsLogAction $settings_log_action
 * @property User $user
 * @property Contact $contact
 *
 * @package App\Models
 */
class ContactLog extends Model
{
	protected $table = 'contact_logs';

	protected $casts = [
		'contact_id' => 'int',
		'action_id' => 'int',
		'author_id' => 'int'
	];

	protected $fillable = [
		'contact_id',
		'action_id',
		'author_id'
	];

	public function settings_log_action()
	{
		return $this->belongsTo(SettingsLogAction::class, 'action_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'author_id');
	}

	public function contact()
	{
		return $this->belongsTo(Contact::class);
	}
}
