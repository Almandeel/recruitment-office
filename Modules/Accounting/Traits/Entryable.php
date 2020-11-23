<?php
namespace Modules\Accounting\Traits;
use Modules\Accounting\Models\Entry;
/**
*  Entryable Trait
*/
trait Entryable
{
    public $has_entry = true;
    public function entry()
    {
        return $this->morphOne(Entry::class, 'entryable');
    }
    
    public function entryConfirm(Request $request = null){
        $request = is_null($request) ? request() : $request;
        $safeableType = $request->type;
        $safeableId = $request->id;
        $safeable = $safeableType::find($safeableId);
        $msg = __("accounting::safes.safeable_confirmed");
        $msg = str_replace('__safeable__', __('accounting::safes.safeables.' . $safeableType), $msg);
        $attributes = [];
        $attributes['entryable_type'] = $request->type;
        $attributes['entryable_id'] = $request->id;
        $attributes['amount'] = $request->amount;
        $attributes['details'] = $request->details;
        $entry = Entry::create($attributes);
        $safeable->entry()->save($entry);
        $from = null;
        $to = null;
        $amount = $request->amount;
        if($safeableType == 'Modules\Accounting\Models\Voucher'){
            if($safeable->isReceipt()){
                $from = $request->safe_id;
                $to = $request->account_id;
            }else{
                $from = $request->account_id;
                $to = $request->safe_id;
            }
        }
        else if($safeableType == 'Modules\Accounting\Models\Expense'){
            $from = $request->account_id;
            $to = $request->safe_id;
        }
        else if($safeableType == 'Modules\Accounting\Models\Transaction'){
            if($safeable->isBonus() || $safeable->isDebt()){
                $from = $request->account_id;
                $to = $request->safe_id;
            }
            
            elseif($safeable->isDeducation()){
                $from = $request->safe_id;
                $to = $request->account_id;
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
        
        return $entry;
    }
    
    public static function bootEntryable(){
        static::updated(function($model){
            // if($model->entry && $model->amount){
            //     $model->entry->update(['amount' => $attributes['amount']]);
            // }
        });
        static::deleted(function($model){
            if($model->entry) {
                \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                $model->entry->delete();
                \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        });
    }
}