<?php

namespace Modules\ExternalOffice\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{Attachable, Statusable};
use Modules\Main\Models\Office;
use Modules\Accounting\Models\Voucher;
class Pull extends BaseModel
{
    use Attachable;
    public const STATUS_WAITING = 0;
    public const STATUS_CONFIRMED = 1;
    public const STATUS_CANCELED = 2;
    public const STATUSES = [
    self::STATUS_WAITING => 'waiting',
    self::STATUS_CONFIRMED => 'confirmed',
    self::STATUS_CANCELED => 'canceled',
    ];
    public const STATUSES_CLASSES = [
    self::STATUS_WAITING => 'secondary',
    self::STATUS_CONFIRMED => 'success',
    self::STATUS_CANCELED => 'danger',
    ];
    protected $fillable = [
    'status',
    'payed',
    'damages',
    'cv_id',
    'user_id',
    'advance_id',
    // 'confirmed',
    // 'cause',
    // 'notes',
    ];
    public function displayType(){
        if ($this->isWaiting()) {
            return __('pulls.types.not_yet');
        }
        elseif (is_null($this->advance_id)) {
            return __('pulls.types.free');
        }
        return __('pulls.types.payed');
    }
    public function getAmount($formated = false){
        $amount = is_null($this->advance_id) ? 0 : $this->advance->amount;
        if($formated && is_null($this->advance_id)) {
            return $this->displayType();
        }
        else if($formated && !is_null($this->advance_id)) {
            return number_format($amount, 2);
        }
        return $amount;
    }
    
    public function totalAmount(){
        return money_formats($this->payed + $this->damages);
    }
    
    public function getStatus($data = 'value'){
        if ($data == 'name') {
            return self::STATUSES[$this->status];
        }
        elseif ($data == 'class') {
            return self::STATUSES_CLASSES[$this->status];
        }
        return $this->status;
    }
    
    public function displayStatus($style = 'badge')
    {
        $classes = $style == 'badge' ? 'badge badge-' : 'text';
        $classes .= $this->getStatus('class');
        $builder = "<span class='$classes'>";
        $builder .=  __('pulls.statuses.' . $this->getStatus('name'));
        $builder .= "</span>";
        
        return $builder;
    }
    
    public function checkStatus($status)
    {
        if (gettype($status) == 'integer') {
            return $this->getStatus() == $status;
        }
        elseif (gettype($status) == 'string') {
            return self::STATUSES[$this->getStatus()] == $status;
        }
        throw new Exception("Unsupported status type", 1);
    }
    
    public function isWaiting()
    {
        return $this->checkStatus(self::STATUS_WAITING);
    }
    
    public function isConfirmed()
    {
        return $this->checkStatus(self::STATUS_CONFIRMED);
    }
    
    public function isCanceled()
    {
        return $this->checkStatus(self::STATUS_CANCELED);
    }
    
    public function confirm($request = null){
        $request = is_null($request) ? request() : $request;
        $amount = 0;
        $amount += $request->payed;
        $amount += $request->damages;
        if($amount){
            $advance = Advance::create([
            'office_id' => $this->cv->office_id,
            'amount' => $amount,
            'details' => 'Converting pulled CV: ' . $request->cv_id . ' payments to advance',
            'status' => Advance::STATUS_PAYED,
            'user_id' => $this->cv->office->admin_id,
            ]);
            
            $voucher = Voucher::create([
            'amount' => $amount,
            'advance_id' => $advance->id,
            'type' => Voucher::TYPE_PAYMENT,
            'details' => 'عبارة عن سند صرف لسحب cv رقم: ' . $request->cv_id . ' بإعتبار إجمالي المدفوع والاضرار كسلفة للمكتب',
            'currency' => 'دولار',
            'voucher_date' => date('Y-m-d'),
            'status' => Statusable::$STATUS_CHECKED,
            ]);
            $request['advance_id'] = $advance->id;
        }
        $request['status']  = self::STATUS_CONFIRMED;
        $succeeded = $this->cv->update(['status' => Cv::STATUS_PULLED]) && $this->update($request->except(['_token', '_method']));
        if ($succeeded) {
            $this->attach($request);
        }
        
        return $succeeded;
    }
    
    public function cancel($request = null){
        return $this->update(['status' => self::STATUS_CANCELED]);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
    
    public function advance(){
        return $this->belongsTo(Advance::class);
    }
    
    public function office()
    {
        return $this->cv->belongsTo(Office::class);
    }
}