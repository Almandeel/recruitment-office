<?php

namespace Modules\Main\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $table = 'suggestions';
    protected $fillable = ['content', 'useful', 'seen' ,'user_id'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
