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
        self::STATUS_TRAIL, self::STATUS_CONFIRMED, self::STATUS_CANCELED
    ];

    protected $fillable = ['status', 'trail_date', 'trail_period', 'contract_id', 'cv_id', 'customer_id', 'x_customer_id', 'x_contract_id'];
    
    public function getDisplayStatusAttribute()
    {
        return __('bails.statuses.' . $this->status);
    }
    public function getStatusAttribute($status)
    {
        $period = $this->period_in_days;
        if ($status == self::STATUS_TRAIL) {
            if ($period < 1) {
                $status = self::STATUS_CANCELED;
            }
        }

        return $status;
    }

    function checkStatus($status)
    {
        return $this->status == $status;
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
