<?php

namespace Modules\Accounting\Models;

use Modules\Employee\Models\Employee;
use Modules\Accounting\Traits\Safeable;
use Modules\Accounting\Traits\Yearable;
use App\Traits\Authable;
use App\Traits\Logable;
use App\Traits\Attachable;
use App\Traits\Statusable;
class Transaction extends BaseModel
{
    use Attachable;
    use Yearable;
    use Safeable;
    use Statusable;
    public const TYPE_DEBT = -1;
    public const TYPE_DEDUCATION = -2;
    public const TYPE_BONUS = 1;
    public const TYPES = [
    self::TYPE_BONUS,
    self::TYPE_DEBT,
    self::TYPE_DEDUCATION,
    ];
    protected $table = 'transactions';
    protected $fillable = ['amount', 'month', 'type', 'details', 'employee_id', 'year_id'];
    
    public function employee(){
        return $this->belongsTo(Employee::class);
    }
    public function getType(){
        return __('transactions.types.' . $this->type);
    }
    public static function getStaticType($type){
        return __('transactions.types.' . $type);
    }
    public function displayType(){
        return __('transactions.types.' . $this->type);
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function isDebt(){ return $this->is(self::TYPE_DEBT); }
    public function isDeducation(){ return $this->is(self::TYPE_DEDUCATION); }
    public function isBonus(){ return $this->is(self::TYPE_BONUS); }
    public function is($type){
        return $this->type == $type;
    }
    
    public function isEditable(){
        return is_null($this->entry);
    }

    public function getSafeAttribute()
    {
        $safe = new Safe();
        $safe->name = 'لا يوجد';
        $entry = $this->entry;
        if ($entry && !$this->isDeducation()) {
            $safe = Safe::find($entry->credits()->first()->id);
        }

        return $safe;
    }

    public function getAccountAttribute()
    {
        $account = new Account();
        $account->name = 'لا يوجد';
        $entry = $this->entry;
        if ($entry && !$this->isDeducation()) {
            $account = $entry->debts()->first();
        }

        return $account;
    }
    
    public function delete(){
        $result = parent::delete();
        
        return $result;
    }
}