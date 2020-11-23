<?php

namespace Modules\Tutorial\Models;

use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    protected $table = 'tutorials';
    protected $fillable = ['title', 'content', 'user_id', 'category_id'];

    public function category() {
        return $this->belongsTo('Modules\Tutorial\Models\Category');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
