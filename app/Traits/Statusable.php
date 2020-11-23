<?php
namespace App\Traits;
use Modules\Accounting\Models\Voucher;
/**
*  Statusable
*/
trait Statusable
{
    public $is_statusable = true;
    
    public static $STATUS_WAITING = 0;
    public static $STATUS_APPROVED = 1;
    public static $STATUS_REJECTED = -1;
    public static $STATUS_CHECKED = 2;
    public static $STATUS_CHECKING = 3;
    public static $STATUSES = [
        0 => 'waiting',
        1 => 'approved',
        3 => 'checking',
        2 => 'checked',
        -1 => 'rejected',
    ];
    public function hasEntry(){
        return $this->has_entry ? true : false;
    }
    public function isSafeable(){
        return $this->is_safeable ? true : false;
    }
    public function getStatus()
    {
        if(is_null($this->entry) || $this->status != self::$STATUS_CHECKED){
            if($this->status == self::$STATUS_APPROVED && is_null($this->entry)){
                return self::$STATUSES[self::$STATUS_CHECKING];
            }
            return self::$STATUSES[$this->status];
        }
        return self::$STATUSES[self::$STATUS_CHECKED];
    }
    
    public function displayStatus()
    {
        // return __('global.statuses.'.$this->getStatus());
        if(get_class($this) == Voucher::class){
            if ($this->statusIsChecked() && $this->isPayment()) {
                return __('accounting::vouchers.statuses.checked_payment');
            }
            else if ($this->statusIsChecked() && $this->isReceipt()) {
                return __('accounting::vouchers.statuses.checked_receipt');
            }
            return __('accounting::vouchers.statuses.'.$this->getStatus());
        }
        return __('accounting::global.statuses.'.$this->getStatus());
        // switch ($this->getStatus()) {
        //     case 'approved':
        //         if(is_null($this->entry)){
        //             return 'في الحسابات';
        //         }
        //         return 'تم التأكيد';
        //         break;
        //     case 'checked':
        //         return 'تم الصرف';
        //     case 'checking':
        //         return 'في الحسابات';
        //         break;
        //     case 'rejected':
        //         return 'تم الرفض';
        //         break;
        //     default:
        //         return 'في انتظار التأكيد';
        //         break;
        // }
    }
    public function statusEquals($status){
        return $this->getStatus() == $status;
    }
    public function statusIsWaiting(){
        return $this->getStatus() == self::$STATUSES[self::$STATUS_WAITING];
    }
    
    public function statusIsApproved(){
        return $this->getStatus() == self::$STATUSES[self::$STATUS_APPROVED];
    }
    
    public function statusIsChecked(){
        return $this->getStatus() == self::$STATUSES[self::$STATUS_CHECKED];
    }
    
    public function statusInChecking(){
        return $this->getStatus() == self::$STATUSES[self::$STATUS_CHECKING];
    }
    
    public function statusIsRejected(){
        return $this->getStatus() == self::$STATUSES[self::$STATUS_REJECTED];
    }
    
    public function statusIs($status){
        if(gettype($status) == 'string'){
            $index = array_search($status, self::$STATUSES);
            if (in_array($status, self::$STATUSES)) {
                return $this->getStatus() == self::$STATUSES[$index];
            }
        }
        else if(gettype($status) == 'integer'){
            if (array_key_exists($status, self::$STATUSES)) {
                return $this->getStatus() == self::$STATUSES[$status];
            }
        }
        
        return false;
    }
    
    public function setStatus($status){
        $this->status = $status;
        return $this->save();
    }
    
    public function setStatusChecked(){
        return $this->setStatus(self::$STATUS_CHECKED);
    }
    
    public function approve(){
        // if($this->hasEntry()){
        //     $this->confirm();
        // }
        
        return $this->setStatus(self::$STATUS_APPROVED);
    }
    
    public function reject(){
        return $this->setStatus(self::$STATUS_REJECTED);
    }
    
    public function changeStatus(){
        if (request()->has('status')) {
            $succeeded = false;
            if(request()->status == 'approve'){
                $succeeded = $this->approve();
            }
            else if(request()->status == 'reject'){
                $succeeded = $this->reject();
            }
            return $succeeded;
            // if($succeeded){
            //     return back()->withSuccess('تم التأكيد بنجاح');
            // }
            
            // return back()->withError('فشلت العملية');
        }
        return false;
        // return back()->withError('لا يمكنك تغيير الحالة');
    }
    
    public static function bootStatusable(){
        static::creating(function($model){
            // if (auth()->user()->isAbleTo($model->getTable() . '-update')) {
            //     $model->status = self::$STATUS_APPROVED;
            // }
        });
    }
}