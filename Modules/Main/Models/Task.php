<?php

namespace Modules\Main\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['name', 'user_id' ,'status' ,'due_date'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function taskUser() {
        return $this->hasMany('Modules\Main\Models\TaskUser');
    }

}
