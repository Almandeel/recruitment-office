<?php

namespace Modules\Tutorial\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name'];

    public function posts() {
        return $this->hasMany('Modules\Tutorial\Models\Tutorial');
    }
}
