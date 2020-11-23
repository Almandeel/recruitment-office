<?php
namespace Modules\Accounting\Traits;
use Modules\Accounting\Models\Safe;
use Modules\Accounting\Models\Entry;
/**
*  Safeable Trait
*/
trait Safeable
{
    use Entryable;
    public $is_safeable = true;
    public function safes()
    {
        return $this->morphToMany(Safe::class, 'safeable');
    }

    public function safe()
    {
        return $this->safes->first();
    }

    public function getSafeAttribute()
    {
        $safes = $this->safes;
        $safe = $safes->count() ? $safes->first() : new Safe();
        return $safe;
    }
    
    public function account(){
        if($this->entry && $this->safe){
            if($this->entry->debts()->contains($this->safe->id)){
                return $this->entry->credits()->first();
            }
            return $this->entry->debts()->first();
        }
        return null;
    }

    // public function getSafeAttribute()
    // {
    //     return $this->morphToMany(Safe::class, 'safeable')->first();
    // }
    
    public function getAccountAttribute(){
        if($this->entry && $this->safe){
            if($this->entry->debts()->contains($this->safe->id)){
                return $this->entry->credits()->first();
            }
            return $this->entry->debts()->first();
        }
        return null;
    }
    
    public function setSafe($safeId)
    {
        $attributes = [];
        $attributes['safe_id'] = $safeId;
        $attributes['safeable_id'] = $this->id;
        $attributes['safeable_type'] = get_class($this);
        \DB::table('safeables')->insert($attributes);
    }
    
    public function isStatusable(){
        return $this->is_safeable ? true : false;
    }
    
    public function confirm($safe_id = null, $account_id = null, $amount = null , $details = null){
        $safe_id = is_null($safe_id) ? request()->safe_id : $safe_id;
        $account_id = is_null($account_id) ? request()->account_id : $account_id;
        $details = $details ? $details : request()->details;
        if(!$this->confirmed()){
            if($safe_id){
                $attributes = [];
                $attributes['safe_id'] = $safe_id;
                $attributes['safeable_id'] = $this->id;
                $attributes['safeable_type'] = get_class($this);
                // dd(implode(', ', array_fill(0, count($attributes), '?')));
                if($this->safe){
                    $safeables = \DB::table('safeables')->update($attributes);
                }else {
                    $safeables = \DB::table('safeables')->insert($attributes);
                }
            }
            
            $safeableId = $this->id;
            $safeableType = get_class($this);
            $amount = is_null($amount) ? $this->amount : $amount;
            $details = is_null($details) ? $this->details : $details;
            
            $safeable = $this;
            $msg = __("accounting::safes.safeable_confirmed");
            $msg = str_replace('__safeable__', __('accounting::safes.safeables.' . $safeableType), $msg);
            $attributes = [];
            $attributes['entryable_type'] = $safeableType;
            $attributes['entryable_id'] = $safeableId;
            $attributes['amount'] = $amount;
            $attributes['details'] = $details;
            $entry = Entry::create($attributes);
            $safeable->entry()->save($entry);
            if(request()->has('debtssss') && request()->has('creditsssss')){
                foreach (request()->debts as $account) {
                    $entry->accounts()->attach($account['id'], [
                    'amount' => $account['amount'],
                    'side' => Entry::SIDE_DEBTS,
                    ]);
                }
                foreach (request()->credits as $account) {
                    $entry->accounts()->attach($account['id'], [
                    'amount' => $account['amount'],
                    'side' => Entry::SIDE_CREDITS,
                    ]);
                }
            }else{
                $from = null;
                $to = null;
                if($safeableType == 'Modules\Accounting\Models\Voucher'){
                    if($safeable->isReceipt()){
                        $from = $safe_id;
                        $to = $account_id;
                    }else{
                        $from = $account_id;
                        $to = $safe_id;
                    }
                }
                else if($safeableType == 'Modules\Accounting\Models\Expense' || 'Modules\Accounting\Models\Salary'){
                    $from = $account_id;
                    $to = $safe_id;
                }
                else if($safeableType == 'Modules\Accounting\Models\Transaction'){
                    if($this->isDebt()){
                        $from = $account_id;
                        $to = $safe_id;
                    }
                    else if($this->isDeducation()){
                        $to = $account_id;
                        $from = $safe_id;
                    }
                    else if($this->isBonus()){
                        $from = $account_id;
                        $to = $safe_id;
                    }
                }
                
                $entry->accounts()->attach($from, [
                'amount' => $amount,
                'side' => Entry::SIDE_DEBTS,
                ]);
                $entry->accounts()->attach($to, [
                'amount' => $amount,
                'side' => Entry::SIDE_CREDITS,
                ]);
            }
            
            if($this->isStatusable()){
                $this->setStatusChecked();
            }
        }
    }
    
    public function confirmed(){
        return !is_null($this->entry);
    }
    
    public function cancelSafe(){
        $safeable = \DB::table('safeables')->where('safeable_id', $model->id);
        if($safeable){
            return $safeable->delete();
        }
        
        return false;
    }
    
    public static function bootSafeable()
    {
        
        static::creating(function($model){
        });
        
        static::created(function($model){
            if(request()->has('safe_id')){
                $attributes = [];
                $attributes['safe_id'] = request()->safe_id;
                $attributes['safeable_id'] = $model->id;
                $attributes['safeable_type'] = get_class($model);
                \DB::table('safeables')->insert($attributes);
            }
        });
        
        static::updating(function($model){
            // ... code here
        });
        
        static::updated(function($model){
            // ... code here
        });
        
        static::deleting(function($model){
            \DB::table('safeables')->where('safeable_id', $model->id)->delete();
        });
        
        static::deleted(function($model){
            // ... code here
        });
    }
}