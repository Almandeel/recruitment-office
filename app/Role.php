<?php

namespace App;

use Laratrust\Models\LaratrustRole;
use App\Traits\Logable;

class Role extends LaratrustRole
{
    use Logable;
    public $guarded = [];

    protected $fillable = [
        'name',
        'office_id',
    ];

    public function scopeMainOfficeRoles($query)
    {
        return $query->where('office_id', null);
    }

    public function scopeOfficeRoles($query)
    {
        return $query->where('office_id', '=', auth()->guard('office')->user()->office_id);
    }
}
