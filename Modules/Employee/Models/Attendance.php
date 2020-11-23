<?php

namespace Modules\Employee\Models;


class Attendance extends BaseModel
{
    protected $table = 'attendance';
    protected $fillable = ['time_in', 'time_out', 'attend_date', 'attended', '	notes', 'employee_id'];


    public function employee() {
        return $this->belongsTo('Modules\Employee\Models\Employee', 'employee_id');
    }
}
