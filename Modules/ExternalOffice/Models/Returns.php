<?php

namespace Modules\ExternalOffice\Models;

use App\Traits\Attachable;
use Illuminate\Database\Eloquent\Model;

class Returns extends BaseModel
{
	use Attachable;

	protected $table = 'returns';

	protected $fillable = [
		'cv_id',
		'user_id',
	];

	public function user()
	{
		return $this->belongsTo('Modules\ExternalOffice\Models\User');
	}

	public function cv()
	{
		return $this->belongsTo('Modules\ExternalOffice\Models\Cv');
	}
}
