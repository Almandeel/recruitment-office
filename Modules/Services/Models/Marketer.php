<?php

namespace Modules\Services\Models;

// use Modules\Accounting\Traits\Voucherable;
use Modules\Accounting\Models\Voucher;
class Marketer extends BaseModel
{
    // use Voucherable;
    protected $fillable = [
    'name',
    'phone',
    'debt',
    'credit'
    ];
    
    public function update(array $attributes = [], array $options = []){
        $result = parent::update($attributes, $options);
        return $result;
    }
    
    public function contracts() {
        return $this->hasMany(Contract::class);
    }
    
    public function tafweeds() {
        return $this->hasMany(Tafweed::class);
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function debts($formated = false){
        $amount = 0;
        
        // $amount = $this->vouchers->filter(function($voucher){
        //     return $voucher->isChecked() && $voucher->type == Voucher::TYPE_PAYMENT;
        // })->sum('entry.amount');

        $amount = $this->vouchers->filter(function($voucher){
            return $voucher->isChecked() && $voucher->type == Voucher::TYPE_PAYMENT;
        })->sum('entry.amount');


        if($formated){
            return number_format($amount, 2);
        }
        
        return $amount;
    }
    
    public function credits($formated = false){
        $amount = 0;
        // $amount = $this->vouchers->filter(function($voucher){
        //     return $voucher->isChecked();
        // })->sum('entry.amount');

        foreach ($this->contracts as $contract) {
            $amount += $contract->getMarketerMoney();
        }
        
        if($formated){
            return number_format($amount, 2);
        }
        
        return $amount;
    }
    
    public function balance(){
        $debts = $this->debts();
        $credits = $this->credits();
        $balance = [
        'amount' => 0,
        'side' => '',
        ];
        
        if($debts > $credits){
            $balance['amount'] = $debts - $credits;
            $balance['side'] = 'مدين';
        }
        
        elseif($debts < $credits){
            $balance['amount'] = $credits - $debts;
            $balance['side'] = 'دائن';
        }
        
        return $balance;
    }
    
    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }
    
    public function displayBalance(){
        $balance = $this->balance();
        if($balance['amount'] > 0){
            return number_format($balance['amount'], 2) . ' ' . $balance['side'];
        }
        return 0;
    }
}