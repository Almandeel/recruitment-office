<?php

namespace Modules\Main\Models;

use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    protected $table = 'task_user';
    protected $fillable = ['task_id', 'user_id'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function task() {
        return $this->belongsTo('Modules\Main\Models\Task', 'task_id');
    }
}
