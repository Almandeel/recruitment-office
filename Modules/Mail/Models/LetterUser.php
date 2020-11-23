<?php

namespace Modules\Mail\Models;

use Illuminate\Database\Eloquent\Model;

class LetterUser extends Model
{
    protected $table = 'letter_user';
    protected $fillable = ['letter_id',	'user_id', 'status', 'box', 'seen'];

    public function letter() {
        return $this->belongsTo('Modules\Mail\Models\Letter', 'letter_id');
    }
}
