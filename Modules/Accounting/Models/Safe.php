<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Collection;
use Modules\Accounting\Traits\Accountable;
class Safe extends BaseModel
{
    use Accountable;
    public const TYPE_CASH = 1;
    public const TYPE_BANK = 2;
    public const TYPES = [
    self::TYPE_CASH,
    self::TYPE_BANK,
    ];
    protected $fillable = [
    'id', 'name', 'type', 'opening_balance'
    ];
    
    public function getType(){
        return __('accounting::safes.types.' . $this->type);
    }
    
    public function isCash(){
        return $this->type == self::TYPE_CASH;
    }
    
    public function isBank(){
        return $this->type == self::TYPE_BANK;
    }
    
    public static function cashes(){
        return self::where('type', self::TYPE_CASH)->get();
    }
    
    public static function banks(){
        return self::where('type', self::TYPE_BANK)->get();
    }
    
    public function cheques()
    {
        return $this->hasMany(Cheque::class, 'account_id');
    }
    
    public function newPayment($data){
        $data['to_id'] = $this->account_id;
        return Entry::newPayment($data);
    }
    
    public function newExpense($data){
        $data['from_id'] = $this->account_id;
        return Entry::newExpense($data);
    }
    
    public function newIncome($data){
        $data['from_id'] = $this->account_id;
        return Entry::newIncome($data);
    }
    
    public function payments(){
        return $this->hasMany(Payment::class);
    }
    
    
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    
    
    // public function transfers()
    // {
    // return $this->hasMany(Transfer::class);
    // }
    
    
    // public function transfers()
    // {
    //     return $this->morphedByMany(Transfer::class, 'safeable');
    // }
    
    public function debts(){
        return year()->transfers->where('from_id', $this->id)->unique();
    }
    
    public function credits(){
        return year()->transfers->where('to_id', $this->id)->unique();
    }
    
    public function billsEntries($from_date = null, $to_date = null)
    {
        $entries = $this->paymentsEntries()->filter(function($entry){
            return $entry->to_id == $this->id;
        });
        if($from_date && $to_date) return $entries->whereBetween('created_at', [$from_date, $to_date]);
        return $entries;
    }
    
    public function paymentsEntries()
    {
        $entries_ids = $this->payments->pluck('entry_id');
        $entries = Entry::whereIn('id', $entries_ids)->get();
        return $entries;
    }
    
    public static function bankSafes($show = null) {
        $safes = Safe::where('type', self::TYPE_BANK);
        if($show){
            $safes->where('show_' . $show, 1);
        }
        return $safes->get();
    }
    
    
    public function entries()
    {
        return $this->account->entries;
    }
    
    
    public static function create(array $attributes = [])
    {
        // $account = ($attributes['type'] == self::TYPE_CASH) ? Account::cashes()->addChild($attributes['name']) : Account::banks()->addChild($attributes['name']);
        // $attributes['id'] = $account->id;
        
        $model = static::query()->create($attributes);
        // $model->account()->save($account);
        return $model;
    }
    
    public function update(array $attributes = [], array $options = []){
        // if($this->account){
        //     $this->account->update([ 'name' => $attributes['name']]);
        // }
        parent::update($attributes, $options);
    }
    
    public function getParent(){
        return Account::cashes();
    }
    
    public function delete(){
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // foreach ($this->transfers as $transfer) {
        //     $transfer->delete();
        // }
        // foreach ($this->payments as $payment) {
        //     $payment->delete();
        // }
        // foreach ($this->expenses as $expense) {
        //     $expense->delete();
        // }
        if($this->account) $this->account->delete();
        $result = parent::delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return $result;
    }
    
    public function cash($amount, $accounts){
        
    }
}