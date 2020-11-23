<?php

namespace Modules\ExternalOffice\Models;

use App\User;
use App\Traits\Attachable;
use Modules\Main\Models\Office;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Traits\Voucherable;
use Modules\ExternalOffice\Models\CvFlight;
use Modules\ExternalOffice\Scopes\OfficeScope;

class Flight extends Model
{
    use Attachable, Voucherable;

    public const STATUS_WAITING = 0;
    public const STATUS_ARRIVING = 1;
    public const STATUS_ARRIVED = 2;
    public const STATUS_NOT_ARRIVED = 3;

    public const STATUSES = [
        self::STATUS_WAITING => 'waiting',
        self::STATUS_ARRIVING => 'arriving',
        self::STATUS_ARRIVED => 'arrived',
        self::STATUS_NOT_ARRIVED => 'notArrived',
    ];

    protected $fillable = [
        'user_id',
        'office_id',
        'country_id',
        'departure_at',
        'arrival_at',
        'departure_airport',
        'arrival_airport',
        'trip_number',
        'airline_name',
        'status',
        'notify_customer'
    ];

    protected $casts = [
        'status' => 'int'
    ];

    protected $dates = [
        'departure_at',
        'arrival_at',
    ];

    public function passengers()
    {
        return $this->hasMany(CvFlight::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OfficeScope());
    }

    public function displayStatus()
    {
        return __("externaloffice::flight.status.{$this->getStatus()}");
    }

    public function getStatus()
    {
        return static::STATUSES[$this->status];
    }
}
