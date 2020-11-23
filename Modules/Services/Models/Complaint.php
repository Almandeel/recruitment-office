<?php

namespace Modules\Services\Models;

use App\Traits\Attachable;
class Complaint extends BaseModel
{
    use Attachable;
    protected $table = 'complaints';
    protected $fillable = [
        'cause', 'status', 'from', 'cv_id', 'user_id', 'customer_id'
    ];


    public function customer() {
        return $this->belongsTo('Modules\Services\Models\Customer', 'customer_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function cv() {
        return $this->belongsTo('Modules\ExternalOffice\Models\Cv', 'cv_id');
    }
}
