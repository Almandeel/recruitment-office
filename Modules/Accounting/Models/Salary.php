<?php

namespace Modules\Accounting\Models;

use Modules\Accounting\Traits\Safeable;
use Modules\Accounting\Traits\Yearable;
// use App\Traits\Authable;
// use App\Traits\Logable;
use App\Traits\Attachable;
use App\Traits\Statusable;
use Modules\Employee\Models\Employee;
class Salary extends BaseModel
{
    // use Logable;
    use Attachable;
    use Yearable;
    use Safeable;
    use Statusable;
    
    protected $table = 'salaries';
    protected $fillable = ['id', 'total', 'net', 'debts', 'deducations', 'bonus', 'month', 'employee_id', 'year_id'];
    
    public function amount(){
        $amount = $this->net;
        $amount += $this->debts;
        $amount += $this->deducations;
        $amount -= $this->bonus;
        return $amount;
    }
    public function displayAmount(){
        return number_format($this->amount(), 2) . ' ريال';
    }
    public function employee(){
        return $this->belongsTo(Employee::class);
    }
    
    public function transactions($type = 'all'){
        $transactions = Transaction::where('employee_id', $this->employee->id)->where('month', $this->month);
        if($type == 'payed'){
            return $transactions->get()->filter(function($transaction){
                return !is_null($transaction->safe());
            });
        }
        elseif($type == 'remain'){
            return $transactions->get()->filter(function($transaction){
                return is_null($transaction->safe());
            });
        }
        
        return $transactions->get();
    }
    
    public static function create($attributes){
        $model = static::query()->create($attributes);
        
        return $model;
    }
    public function split_month($index = 0){
        return explode('-', $this->month)[$index];
    }
    // public function delete(){
    //     parent::delete();
    // }
}