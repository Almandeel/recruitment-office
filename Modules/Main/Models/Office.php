<?php

namespace Modules\Main\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Modules\Accounting\Models\Voucher;
use Modules\ExternalOffice\Models\{Cv, Returned};
use Modules\Accounting\Traits\Voucherable;
use Modules\Accounting\Traits\Accountable;


class Office extends Model
{
    use Voucherable, Accountable;
    
    protected $fillable = [
    'name',
    'status',
    'country_id',
    'email',
    'phone',
    'admin_id',
    ];
    
    public function displayStatus()
    {
        return 'نشط';
    }
    
    public function credits($formated = false)
    {
        $credits = $this->cvs->where('status', Cv::STATUS_ACCEPTED)->sum('amount');
        // dd($this->receipts()->pluck('details')->toArray(), $this->receipts()->sum('amount'), $credits);
        $credits += $this->receipts()->sum('amount');
        if ($formated) {
            return number_format($credits, 2);
        }
        return $credits;
    }
    
    public function debts($formated = false)
    {
        // dd($this->payments()->pluck('details', 'amount')->toArray());
        // $payed = 0;
        // foreach ($this->bills as $bill) {
        //     $payed += $bill->vouchers->filter(function($voucher){
        //         return $voucher->isChecked();
        //     })->sum('amount');
        // }
        
        $payed = $this->payments()->sum('amount');
        
        if ($formated) {
            return number_format($payed, 2);
        }
        return $payed;
    }
    
    public function vouchers($status = null, $type = null)
    {
        return  $this->billsVouchers($status, $type)->merge($this->advancesVouchers($status, $type));
    }
    
    public function billsVouchers($status = null, $type = null)
    {
        $status = is_null($status) ? 'all' : $status;
        $type = is_null($type) ? 'all' : $type;
        $vouchers = new Collection();
        
        foreach ($this->bills as $bill) {
            $vouchers = $vouchers->merge($bill->vouchers->filter(function ($voucher) use ($status, $type) {
                $condition = true;
                
                if ($status != 'all') {
                    $condition = $voucher->statusIs($status);
                }
                if ($type != 'all') {
                    if ($status != 'all') {
                        $condition = ($condition && $voucher->typeIs($type));
                    }else{
                        $condition = $voucher->typeIs($type);
                    }
                }
                
                return $condition;
            }));
        }
        
        return $vouchers;
    }
    
    public function advancesVouchers($status = null, $type = null)
    {
        $status = is_null($status) ? 'all' : $status;
        $type = is_null($type) ? 'all' : $type;
        $vouchers = new Collection();
        
        foreach ($this->advances as $advance) {
            $vouchers = $vouchers->merge($advance->vouchers->filter(function ($voucher) use ($status, $type) {
                $condition = true;
                
                if ($status != 'all') {
                    $condition = $voucher->statusIs($status);
                }
                if ($type != 'all') {
                    $condition = ($condition && $voucher->typeIs($type));
                }
                
                return $condition;
            }));
        }
        
        return $vouchers;
    }
    
    public function getWaiting(){
        return Cv::getWaiting(['office_id' => $this->id]);
    }
    
    public function getAccepted(){
        return Cv::getAccepted(['office_id' => $this->id]);
    }
    
    public function getContracted(){
        return Cv::getContracted(['office_id' => $this->id]);
    }
    
    public function getPulled(){
        return Cv::getPulled(['office_id' => $this->id]);
    }
    
    public function getCanceled(){
        return Cv::getCanceled(['office_id' => $this->id]);
    }
    
    public function receipts($status = 'checked')
    {
        return $this->vouchers($status, 'receipt');
    }
    
    public function payments($status = 'checked')
    {
        return $this->vouchers($status, 'payment');
    }
    
    public function balance($display = false)
    {
        $debts = $this->debts();
        $credits = $this->credits();
        $balance = [
        'amount' => 0,
        'side' => '',
        ];
        
        if ($debts > $credits) {
            $balance['amount'] = $debts - $credits;
            $balance['side'] = 'debt';
        } elseif ($debts < $credits) {
            $balance['amount'] = $credits - $debts;
            $balance['side'] = 'credit';
        }
        if (gettype($display) == 'string') {
            if (array_key_exists($display, $balance)) {
                return $balance[$display];
            }
        }
        if ($display) {
            if ($balance['amount'] == 0) {
                return number_format($balance['amount'], 2);
            }
            return number_format($balance['amount'], 2) . ' ' . __('global.balance_sides.' . $balance['side']);
        }
        return $balance;
    }
    
    public function displayBalance()
    {
        $balance = $this->balance();
        if ($balance['amount'] > 0) {
            return number_format($balance['amount'], 2) . ' ' . $balance['side'];
        }
        return 0;
    }
    
    public function country()
    {
        return $this->belongsTo('Modules\ExternalOffice\Models\Country');
    }
    
    public function admin()
    {
        return $this->belongsTo('Modules\ExternalOffice\Models\User', 'admin_id');
    }
    
    public function cvs($type = null)
    {
        if ($type) {
            switch ($type) {
                case 'contracted':
                    return $this->cvs->filter(function ($cv) {
                        return !is_null($cv->contract_id);
                });
                
                default:
                    return $this->cvs->filter(function ($cv) {
                        return is_null($cv->contract_id);
                });
            }
        }
        return $this->hasMany('Modules\ExternalOffice\Models\Cv');
    }
    
    public function getContractsAttribute()
    {
        $cvs = Cv::where('office_id', $this->id)->where('status', Cv::STATUS_CONTRACTED)->get();
        $contracts = new Collection();
        foreach ($cvs as $cv) {
            $contracts = $contracts->merge($cv->contracts);
        }

        return $contracts;
    }
    public function contracts()
    {
        // $cvs = $this->cvs('contracted');
        $cvs = Cv::where('office_id', $this->id)->where('status', Cv::STATUS_CONTRACTED)->get();
        $contracts = new Collection();
        foreach ($cvs as $cv) {
            $contracts->push($cv->contract);
        }
        return $contracts;
    }
    
    
    public function returns()
    {
        return $this->hasManyThrough(Returned::class, Cv::class);
    }
    
    
    public function advances()
    {
        return $this->hasMany('Modules\ExternalOffice\Models\Advance');
    }
    
    public function bills()
    {
        return $this->hasMany('Modules\ExternalOffice\Models\Bill');
    }
    
    public function pulls()
    {
        return $this->hasMany('Modules\ExternalOffice\Models\Pull');
    }
    
    public function users()
    {
        return $this->hasMany('Modules\ExternalOffice\Models\User');
    }
    
    // public function bills() {
    // 	return $this->hasMany('Modules\Customer\Models\Bill');
    // }
}