<?php

namespace Modules\Accounting\Models;


// use Modules\Accounting\Traits\Authable;
use App\Traits\Statusable;
use Modules\Accounting\Traits\Safeable;
use Modules\Accounting\Traits\Yearable;

class Expense extends BaseModel
{
    use Yearable;
    use Safeable;
    use Statusable;
    // public $traits = ['Authable', 'Safeable', 'Safeable'];
    protected $fillable = ['id', 'amount', 'details', 'status', 'safe_id', 'account_id', 'year_id'];

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function safe(){
        return $this->belongsTo(Safe::class);
    }

    public function bill(){
        return $this->belongsTo(Bill::class);
    }

    public static function getAll(){
        if(auth()->user()->isSuper()) return static::all();
        return auth()->user()->expenses;
    }

    public static function create(array $attributes = [])
    {
        $model = static::query()->create($attributes);
        $entry = Entry::create([
        'amount' => $model->amount,
        'details' => $model->details,
        'entry_date' => $model->created_at->format('Y-m-d'),
        ]);
        $entry->accounts()->attach($model->account_id, [
        'amount' => $model->amount,
        'side' => Entry::SIDE_DEBTS,
        ]);
        $entry->accounts()->attach($model->safe_id, [
        'amount' => $model->amount,
        'side' => Entry::SIDE_CREDITS,
        ]);

        $model->entry()->save($entry);

        return $model;
    }

    public function update(array $attributes = [], array $options = []){
        $result = parent::update($attributes, $options);
        $entry = $this->entry;
        $entry->update(['amount' => $this->amount, 'details' => $this->details]);
        $debt = $entry->debts()->first()->pivot;
        $credit = $entry->credits()->first()->pivot;

        if($this->account_id == $debt->account_id){
            $debt->amount = $this->amount;
            $debt->save();
        }else{
            // $entry->accounts()->detach([$debt->account_id]);
            foreach ($entry->debts() as $account) {
                $account->pivot->delete();
            }
            $entry->accounts()->attach($this->account_id, [
                'amount' => $this->amount,
                'side' => Entry::SIDE_DEBTS,
            ]);
        }

        if($this->safe_id == $credit->account_id){
            $credit->amount = $this->amount;
            $credit->save();
        }else{
            foreach ($entry->credits() as $account) {
                $account->pivot->delete();
            }
            $entry->accounts()->attach($this->safe_id, [
                'amount' => $this->amount,
                'side' => Entry::SIDE_CREDITS,
            ]);
        }
        return $result;
    }
    public function resetBill(){
        $chargePerItem = $this->bill->items->count() ? $this->amount / $this->bill->items->count() : 0;
        foreach ($this->bill->items as $item) {
            $chargePerUnit = $chargePerItem ? $chargePerItem / $item->quantity : 0;
            $item->update([
            'expense' => $item->expense - $chargePerUnit,
            'expenses' => $item->expenses - $chargePerItem,
            ]);
        }
        $this->bill->bill->refresh();
    }
    public function resetInvoice(){
        $chargePerItem = $this->invoice->items->count() ? $this->amount / $this->invoice->items->count() : 0;
        foreach ($this->invoice->items as $item) {
            $chargePerUnit = $chargePerItem ? $chargePerItem / $item->quantity : 0;
            $item->update([
            'expense' => $item->expense - $chargePerUnit,
            'expenses' => $item->expenses - $chargePerItem,
            ]);
        }
        $this->invoice->invoice->refresh();
    }
    public function delete(){
        $result = parent::delete();
        return $result;
    }
}
