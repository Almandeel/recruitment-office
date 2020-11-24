<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ExternalOffice\Models\Cv;
use Carbon\Carbon;
class Bail extends Model
{
    public const STATUS_TRAIL = 'trail';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PENDING = 'pending';
    public const STATUS_CANCELED = 'canceled';
    public const STATUSES = [
    self::STATUS_TRAIL, self::STATUS_CONFIRMED, self::STATUS_CANCELED
    ];
    
    protected $fillable = ['status', 'amount', 'bail_date', 'notes', 'trail_date', 'trail_period', 'contract_id', 'cv_id', 'customer_id', 'x_customer_id', 'x_contract_id', 'user_id'];
    
    public function getDisplayStatusAttribute()
    {
        return __('bails.statuses.' . $this->status);
    }
    public function getStatusAttribute($status)
    {
        $period = $this->remain_period_in_days;
        if ($status == self::STATUS_TRAIL) {
            if ($period < 1) {
                $status = self::STATUS_PENDING;
            }
        }
        
        return $status;
    }
    
    public function isTrail()
    {
        return $this->checkStatus('trail');
    }
    
    function checkStatus($status)
    {
        return $this->status == $status;
    }
    
    public function getRemainPeriodInDaysAttribute()
    {
        $trail = new Carbon($this->trail_date . '00:00:00');
        $last_day = $trail->addDays($this->trail_period);
        $now = Carbon::now();
        return $last_day->diff($now)->days;
    }
    
    public function getDisplayPeriodInDaysAttribute()
    {
        $period = $this->trail_period;
        $period_text = 'منتهية';
        if ($period > 0) {
            if ($period == 1) {
                $period_text = 'يوم';
            }
            elseif ($period == 2) {
                $period_text = 'يومان';
            }
            elseif ($period > 2 && $period < 11) {
                $period_text = $period . ' ايام';
            }
            elseif ($period > 10) {
                $period_text = $period . ' يوم';
            }
        }
        
        return $period_text;
    }
    
    public function getDisplayRemainPeriodInDaysAttribute()
    {
        $period = $this->remain_period_in_days;
        $period_text = 'منتهية';
        if ($period > 0) {
            if ($period == 1) {
                $period_text = 'يوم';
            }
            elseif ($period == 2) {
                $period_text = 'يومان';
            }
            elseif ($period > 2 && $period < 11) {
                $period_text = $period . ' ايام';
            }
            elseif ($period > 10) {
                $period_text = $period . ' يوم';
            }
        }
        
        return $period_text;
    }
    
    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function x_customer()
    {
        return $this->belongsTo(Customer::class, 'x_customer_id');
    }
    
    
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    
    public function x_contract()
    {
        return $this->belongsTo(Contract::class, 'x_contract_id');
    }
    
}