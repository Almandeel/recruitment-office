<?php

namespace Modules\ExternalOffice\Models;

use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use LaratrustUserTrait, Notifiable;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'office_users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'username',
		'password',
		'phone',
		'status',
		'office_id',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	public function scopeActive($query)
	{
		return $query->where('status', 1);
	}

	public function scopeInSameOffice($query)
	{
		$query->where('office_id', '=', auth()->guard('office')->user()->office_id);
	}

	public function office()
	{
		return $this->belongsTo('Modules\Main\Models\Office');
	}
}
