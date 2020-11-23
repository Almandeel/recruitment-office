<?php

namespace Modules\ExternalOffice\Models;

use Illuminate\Database\Eloquent\Model;

class Marketer extends BaseModel
{
    protected $fillable = [
    	'name',
        'phone',
        'debt',
        'credit'
    ];
}
