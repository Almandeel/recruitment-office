<?php

namespace Modules\ExternalOffice\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends BaseModel
{
    protected $fillable = [
    	'name',
    	'name_en',
    	'default',
    ];
}
