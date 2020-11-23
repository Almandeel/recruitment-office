<?php

namespace Modules\Accounting\Models;
use Illuminate\Database\Eloquent\Collection;

// use Modules\Accounting\Models\Account;

class Year extends BaseModel
{
    /*
    * Year Types
    */
    public const STATUS_OPENED   = 1;
    public const STATUS_CLOSED   = 2;
    public const STATUS_ARCHIVED = 3;
    public const STATUS_ACTIVE = 4;
    public const STATUSES = [
    self::STATUS_OPENED,
    self::STATUS_CLOSED,
    self::STATUS_ARCHIVED,
    self::STATUS_ACTIVE,
    ];
    
    public const STATUSES_CLASSESS = [
        self::STATUS_OPENED => 'info',
        self::STATUS_CLOSED => 'danger',
        self::STATUS_ARCHIVED => 'warning',
        self::STATUS_ACTIVE => 'success',
    ];
    
    protected $fillable = [
    'id', 'status', 'active', 'closed_at', 'opened_at', 'taxes', 'default_cash',
    'default_bank', 'default_expenses', 'default_revenues',
    ];
    
    public static function last()
    {
        $last = static::orderBy('updated_at', 'DESC')->limit(1)->first();
        if(is_null($last)){
            $last = new static;
            $last->id = 0;
        }
        
        return $last;
    }
    
    public function entries($date = null)
    {
        if($date){
            return $this->entries->where('created_at', 'like', '&' . $date . '%');
        }
        return $this->hasMany(Entry::class);
        // return $this->morphedByMany(Entry::class, 'yearable');
    }
    
    public function vouchers($date = null)
    {
        if($date){
            return $this->vouchers->where('created_at', 'like', '&' . $date . '%');
        }
        return $this->hasMany(Voucher::class);
        // return $this->morphedByMany(Voucher::class, 'yearable');
    }
    public function expenses($date = null)
    {
        if($date){
            return $this->expenses->where('created_at', 'like', '&' . $date . '%');
        }
        return $this->hasMany(Expense::class);
        // return $this->morphedByMany(Expense::class, 'yearable');
    }
    public function accounts(){
        $entries = $this->entries;
        $accounts = new Collection();
        foreach ($entries as $entry) {
            $accounts = $accounts->merge($entry->accounts);
        }
        return $accounts->unique();
    }
    public function transfers($date = null)
    {
        if($date){
            return $this->transfers->where('created_at', 'like', '&' . $date . '%');
        }
        return $this->hasMany(Transfer::class);
        // return $this->morphedByMany(Transfer::class, 'yearable');
    }
    
    public function increase($col){
        $column = $col . '_count';
        $value = $this->setAI($col);
        $this->update([$column => $value]);
        
        return $value;
    }
    
    public function setAI($col){
        $column = $col . '_count';
        $next = intval($this->$column) + 1;
        return  $next;
    }
    
    public function getStatus(){
        switch ($this->status) {
            case self::STATUS_OPENED:
                if(year()){
                    if(yearId() == $this->id){
                        return self::STATUS_ACTIVE;
                }
            }
            return self::STATUS_OPENED;
            default:
                return $this->status;
        }
    }
    
    public function getStatusClass(){
        return self::STATUSES_CLASSESS[$this->getStatus()];
    }
    
    public function displayStatus(){
        return __('accounting::years.statuses.' . $this->getStatus());
    }
    
    public function isOpened(){
        return $this->status == Year::STATUS_OPENED;
    }
    
    public function isClosed(){
        return $this->status == Year::STATUS_CLOSED;
    }
    
    public function isArchived(){
        return $this->status == Year::STATUS_ARCHIVED;
    }
    
    public function isActive(){
        if($this->active == 1){
            // $last_active_year = static::where('active', 1)->orderBy('updated_at', 'DESC')->limit(1)->get()->last();
            $last_active_year = year();
            if (!is_null($last_active_year)) {
                return $last_active_year->id == $this->id;
            }
        }
        
        return false;
    }
    
    public function generateEntryId($count = null){
        $counter = $count ? $count : year()->entries->count();
        $counter = ($counter <= 0) ? 1 : $counter;
        $id = yearId() . $counter;
        if(Entry::find($id)){
            $id = $this->generateEntryId($count + 1);
        }
        return $id;
    }
    
    public function generateVoucherId($count = null){
        $counter = $count ? $count : year()->vouchers->count();
        $counter = ($counter <= 0) ? 1 : $counter;
        $id = yearId() . $counter;
        if(Entry::find($id)){
            $id = $this->generateVoucherId($count + 1);
        }
        return $id;
    }
    
    public function cashAccount(){
        return $this->belongsTo(Account::class, 'default_cash');
    }
    
    public function bankAccount(){
        return $this->belongsTo(Account::class, 'default_bank');
    }
    
    public function revenuesAccount(){
        return $this->belongsTo(Account::class, 'default_revenues');
    }
    
    public function expensesAccount(){
        return $this->belongsTo(Account::class, 'default_expenses');
    }
    
    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    
    public function lastYear(){
        return $this->belongsTo(Year::class, 'last_year');
    }
    
    public function reverseClose(){
        $this->entries()->where('type', Entry::TYPE_CLOSE)->delete();
    }
    public function reset(){
        // \DB::transaction(function()
        // {
        //     $this->entries()->delete();
        //     $this->cheques()->delete();
        //     $this->vouchers()->delete();
        // });
        // $this->update([
        //     'entries_count' => 0,
        //     'cheques_count' => 0,
        //     'vouchers_count' => 0,
        // ]);
        foreach ($this->vouchers as $voucher) {
            $voucher->delete();
        }
        foreach ($this->expenses as $expense) {
            $expense->delete();
        }
        foreach ($this->transactions as $transaction) {
            $transaction->delete();
        }
        foreach ($this->salaries as $salary) {
            $salary->delete();
        }
        foreach ($this->entries as $entry) {
            $entry->delete();
        }
    }
    
    public static function active()
    {
        return static::where('active', 1)->orderBy('updated_at', 'DESC')->limit(1)->get()->last();
    }
    
    public function delete()
    {
        $this->entries()->delete();
        $result = parent::delete();
        
        return $result;
    }
}