<?php
namespace Modules\Accounting\Traits;
use Modules\Accounting\Models\Voucher;
/**
*  Voucherable Trait
*/
trait Voucherable
{
    
    public function vouchers()
    {
        return $this->morphMany(Voucher::class, 'voucherable');
    }
    
    public function getVouchersAttribute()
    {
        return Voucher::where('voucherable_type', get_class($this))->where('voucherable_id', $this->id)->get();
    }

    public function getName(){
        return $this->name;
    }
    
    public function getCurrency(){
        return 'ريال';
    }
    
    public function addVoucher($type, $amount, $details = ''){
        $voucher = Voucher::create([
            'type' => $type,
            'amount' => $amount,
            'details' => $details,
        ]);

        if($voucher){
            $this->vouchers()->save($voucher);
            return true;
        }

        return false;
    }

    public function vouch($data){
        if (gettype($data) == 'array') {
            $voucher = Voucher::create($data);
            $this->vouchers()->save($voucher);
            return $voucher;
        }
    }

    public static function bootVoucherable(){
        static::deleting(function($model){
            if($model->vouchers){
                foreach ($model->vouchers as $voucher) {
                    $voucher->delete();
                }
            }
        });
    }
}