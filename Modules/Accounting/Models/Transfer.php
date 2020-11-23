<?php

namespace Modules\Accounting\Models;


// use Modules\Accounting\Traits\Authable;
use Modules\Accounting\Traits\Entryable;
use Modules\Accounting\Traits\Yearable;
class Transfer extends BaseModel
{
    use Yearable;
    use Entryable;
    
    protected $fillable = ['id', 'amount', 'details', 'from_id', 'to_id'];
    
    public function reverse($details = null){
        $from_id = $this->to_id;
        $to_id = $this->from_id;
        $details = isset($details) ? $details : $this->details;
        return Transfer::create(['from_id' => $from_id, 'to_id' => $to_id, 'amount' => $this->amount, 'details' => $details]);
    }
    
    public function from(){
        // $safe = $this->belongsTo(Safe::class, 'from_id');
        // if($safe){
        //     return $safe;
        // }
        
        return $this->belongsTo(Account::class, 'from_id');
    }
    
    public function to(){
        // $safe = $this->belongsTo(Safe::class, 'to_id');
        // if($safe){
        //     return $safe;
        // }
        
        return $this->belongsTo(Account::class, 'to_id');
    }
    
    public static function create(array $attributes = [])
    {
        $model = static::query()->create($attributes);
        $entry = Entry::create([
            'amount' => $model->amount,
            'details' => $model->details,
            'entry_date' => $model->created_at->format('Y-m-d'),
        ]);
        $entry->accounts()->attach($model->from_id, [
            'amount' => $model->amount,
            'side' => Entry::SIDE_DEBTS,
        ]);
        $entry->accounts()->attach($model->to_id, [
            'amount' => $model->amount,
            'side' => Entry::SIDE_CREDITS,
        ]);

        $model->entry()->save($entry);
        
        return $model;
    }
    
    public function update(array $attributes = [], array $options = [])
    {
        $result = parent::update($attributes, $options);
        $entry = $this->entry;
        $entry->update(['amount' => $this->amount, 'details' => $this->details]);
        $debt = $entry->debts()->first()->pivot;
        $credit = $entry->credits()->first()->pivot;
        
        if($this->from_id == $debt->account_id){
            $debt->amount = $this->amount;
            $debt->save();
        }else{
            foreach ($entry->debts() as $account) {
                $account->pivot->delete();
            }
            $entry->accounts()->attach($this->from_id, [
                'amount' => $this->amount,
                'side' => Entry::SIDE_DEBTS,
            ]);
        }
        
        if($this->to_id == $credit->account_id){
            $credit->amount = $this->amount;
            $credit->save();
        }else{
            foreach ($entry->credits() as $account) {
                $account->pivot->delete();
            }
            $entry->accounts()->attach($this->to_id, [
                'amount' => $this->amount,
                'side' => Entry::SIDE_CREDITS,
            ]);
        }
        
        return $result;
    }
    
    public function delete()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // $this->entry->delete();
        $result = parent::delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return $result;
    }
}