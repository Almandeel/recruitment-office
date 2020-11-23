<?php

namespace Modules\ExternalOffice\Models;

use App\Traits\Attachable;
use Illuminate\Database\Eloquent\Model;
use Modules\ExternalOffice\Scopes\OfficeScope;
use Modules\Accounting\Models\Voucher;

class Advance extends BaseModel
{
    use Attachable;
    public const STATUS_WAITING = 0;
    public const STATUS_PAYED = 1;
    public const STATUS_CANCELED = 2;
    public const STATUSES = [
    'waiting' => self::STATUS_WAITING,
    'payed' => self::STATUS_PAYED,
    'canceled' => self::STATUS_CANCELED,
    ];
    protected $fillable = [
    'amount', 'status', 'user_id', 'office_id', 'voucher_id',
    ];
    
    public function voucher()
    {
        return $this->vouchers->first();
        // return $this->belongsTo(Voucher::class);
    }
    
    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }
    
    public function returned()
    {
        return $this->hasOne(Returned::class);
    }
    
    public function isVouched()
    {
        return $this->vouchers->count() > 0;
        // return !is_null($this->voucher_id);
    }
    public function displayStatus()
    {
        if ($this->isPayed() || $this->hasReturn()) {
            return __('accounting::global.payed');
        }
        if ($this->voucher()) return $this->voucher()->displayStatus();
        return __('accounting::vouchers.waiting');
        // if ($this->isVouched()) {
        //     if ($this->voucher()->isChecked()) {
        //         return 'تم الصرف';
        //     }
        //     return 'في الحسابات';
        // }
        
        // return 'في انتظار السند';
    }
    
    public function hasReturn(){
        return !is_null($this->returned);
    }
    
    public function scopeVouched($query)
    {
        return $query->where('voucher_id', '!=', null);
    }
    
    public function payed($formated = false)
    {
        $payed = $this->vouchers->filter(function ($voucher) {
            return $voucher->isChecked() && $voucher->id != $this->voucher()->id;
        })->sum('amount');
        
        if ($formated) {
            return number_format($payed, 2);
        }
        return $payed;
    }
    
    public function remain($formated = false)
    {
        $remain = $this->amount - $this->payed();
        if ($formated) {
            return number_format($remain, 2);
        }
        return $remain;
    }
    
    public function isPayed()
    {
        return $this->remain() == 0;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function office()
    {
        return $this->belongsTo('Modules\Main\Models\Office');
    }
    
    public function update(array $attributes = [], array $options = [])
    {
        
        $result = parent::update($attributes, $options);
        if(!is_null($this->voucher())){
        //     if ($this->voucher()->amount !== $this->amount) {
        //         $this->voucher()->update(['amount' => $this->amount]);
        //     }
        }
        return $result;
    }
    
    public static function boot()
    {
        static::addGlobalScope(new OfficeScope());
        
        parent::boot();
        
        self::creating(function ($model) {
            // $model->office_id = auth()->guard('office')->user()->office_id;
        });
    }
}