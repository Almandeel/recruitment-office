<?php

namespace Modules\Employee\Models;

use App\Traits\Attachable;

class Vacation extends BaseModel
{
    use Attachable;
    protected $table = 'vacations';
    protected $fillable = ['title', 'details', 'accepted', 'payed', 'started_at', 'ended_at', 'employee_id'];
    
    public function update(array $attributes = [], array $options = []){
        $this->resetAttachments();
        return parent::update($attributes, $options);
    }
    public function employee() {
        return $this->belongsTo('Modules\Employee\Models\Employee', 'employee_id');
    }
}