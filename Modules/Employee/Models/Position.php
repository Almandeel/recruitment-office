<?php

namespace Modules\Employee\Models;


class Position extends BaseModel
{
    protected $table = 'positions';
    protected $fillable = ['title'];

    public function employees() {
        return $this->hasMany('Modules\Employee\Models\Employee');
    }
}
