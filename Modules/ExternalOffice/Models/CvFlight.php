<?php

namespace Modules\ExternalOffice\Models;

use App\Traits\Attachable;
use Modules\ExternalOffice\Models\Cv;
use Modules\ExternalOffice\Models\Flight;
use Modules\Accounting\Traits\Voucherable;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CvFlight extends Pivot
{
    use Attachable, Voucherable;

    public const STATUS_WAITING = 0;
    public const STATUS_ARRIVING = 1;
    public const STATUS_ARRIVED = 2;
    public const STATUS_HOUSED = 3;
    public const STATUS_RECIVED = 4;
    public const STATUS_NOT_ARRIVED = 5;

    public const STATUSES = [
        self::STATUS_WAITING => 'waiting',
        self::STATUS_ARRIVING => 'arriving',
        self::STATUS_ARRIVED => 'arrived',
        self::STATUS_HOUSED => 'housing',
        self::STATUS_RECIVED => 'recivied',
        self::STATUS_NOT_ARRIVED => 'notArrived',
    ];

    public const STATUS_CUSTOMER_WAITING = 0;
    public const STATUS_CUSTOMER_NOTIFIED = 1;
    public const STATUS_CUSTOMER_RECIVED = 2;

    public const CUSTOMER_STATUSES = [
        self::STATUS_CUSTOMER_WAITING => 'waiting',
        self::STATUS_CUSTOMER_NOTIFIED => 'notified',
        self::STATUS_CUSTOMER_RECIVED => 'recivied',
    ];

    public $incrementing = true;

    protected $fillable = [
        'flight_id',
        'cv_id',
        'status',
        'customer_status',
    ];

    protected $casts = [
        'status' => 'int'
    ];

    public function cv()
    {
        return $this->hasOne(Cv::class, 'id', 'cv_id');
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function displayStatus()
    {
        return __("externaloffice::flight.passenger.{$this->getStatus()}");
    }

    public function getStatus()
    {
        // dd($this->status, $this->customer_status);
        return static::STATUSES[$this->status];
    }

    public function displayCustomerStatus()
    {
        return __("externaloffice::flight.customer.{$this->getCustomerStatus()}");
    }

    public function getCustomerStatus()
    {
        return static::CUSTOMER_STATUSES[$this->customer_status];
    }
}
