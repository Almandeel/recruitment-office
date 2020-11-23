<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ExternalOffice\Models\Cv;
use Carbon\Carbon;
class Bail extends Model
{
    public const STATUS_TRAIL = 'trail';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELED = 'canceled';
    public const STATUSES = [
        STATUS_TRAIL, STATUS_CONFIRMED, STATUS_CANCELED
    ];

    protected $fillable = ['status', 'trail_date', 'trail_period', 'contract_id', 'cv_id', 'customer_id', 'x_customer_id', 'x_contract_id'];
    
    public function getStatusAttribute($status)
    {
        $period = $this->period_in_days;
        if ($status == self::STATUS_TRAIL) {
            if ($period < 1) {
                $status = self::STATUS_CANCELED;
            }
        }
        return __('bails.statuses.' . $status);
    }

    public function getPeriodInDaysAttribute()
    {
        $trail = new Carbon($price->trail . '00:00:00');
        $now = Carbon::now();
        return $trail->diff($now)->days;
    }

    public function getDisplayPeriodAttribute()
    {
        $period = $this->period_in_days;
        if ($period) {
            
        }
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
