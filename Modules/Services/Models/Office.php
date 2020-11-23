<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ExternalOffice\Models\User;
class Office extends BaseModel
{
    protected $fillable = [
	    'name',
	    'status',
	    'country_id',
	    'email',
	    'phone',
	    'admin_id',
	];
	
	
	public function admin()
	{
		// return $this->users->first();
		return $this->belongsTo(User::class, 'admin_id');
	}

	public function addUser(array $attributes = []){
		$attributes['office_id'] = $this->id;
		return User::create($attributes);
	}

	
	public function users()
	{
		return $this->hasMany(User::class);
	}
	
	
}
