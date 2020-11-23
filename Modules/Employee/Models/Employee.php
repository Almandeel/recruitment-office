<?php

namespace Modules\Employee\Models;

use App\Traits\Attachable;
use App\Traits\Statusable;
use Modules\Accounting\Models\{Salary, Transaction};
use Modules\Accounting\Traits\Accountable;
class Employee extends BaseModel
{
    use Accountable;
    use Attachable;
    protected $table = 'employees';
    protected $fillable = [
    'name', 'started_at', 'salary' , 'department_id', 'position_id', 'line', 'public_line'
    ];
    
    
    public function user(){
        return $this->hasOne('App\User');
    }
    
    public function transactions(){
        return $this->hasMany('Modules\Accounting\Models\Transaction');
    }
    
    public function position(){
        return $this->belongsTo('Modules\Employee\Models\Position');
    }
    
    public function department(){
        return $this->belongsTo('Modules\Employee\Models\Department');
    }
    
    public function salaries(){
        return $this->hasMany('Modules\Accounting\Models\Salary');
    }
    
    public function attendances(){
        return $this->hasMany('Modules\Employee\Models\Attendance');
    }
    
    public function vacations(){
        return $this->hasMany('Modules\Employee\Models\Vacation');
    }
    
    public function monthlyTransactions($month = null, $approvedOnly = false){
        $month = $month == null ? date('Y-m') : $month;
        if($approvedOnly){
            return Transaction::where('employee_id', $this->id)->where('month', $month)->get()->filter(function($trans){
                return $trans->statusIsCHecked();
            });
        }
        return Transaction::where('employee_id', $this->id)->where('month', $month)->get();
    }
}