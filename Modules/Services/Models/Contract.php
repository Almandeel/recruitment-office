<?php

namespace Modules\Services\Models;
use Illuminate\Database\Eloquent\Collection;
use Modules\ExternalOffice\Models\{Country, Profession, Cv, Bill};
use App\Traits\Attachable;
use Modules\Accounting\Traits\Voucherable;
use Modules\Accounting\Models\Voucher;
use App\Casts\FloatNumber;
class Contract extends BaseModel
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => FloatNumber::class,
        'marketing_ratio' => FloatNumber::class,
        'visa' => 'int'
    ];

    use Attachable;
    use Voucherable;
    public const STATUS_INITIAL    = -1;
    public const STATUS_WAITING    = 0;
    public const STATUS_WORKING    = 1;
    public const STATUS_CANCELED   = 2;
    public const STATUS_TRAIL       = 4;
    public const STATUS_BAILED     = 5;
    public const STATUS_FINISHED   = 6;
    public const STATUSES = [
        self::STATUS_INITIAL => 'initial',
        self::STATUS_WAITING => 'waiting',
        self::STATUS_WORKING => 'working',
        self::STATUS_CANCELED => 'canceled',
        self::STATUS_TRAIL => 'trail',
        self::STATUS_BAILED => 'bailed',
        self::STATUS_FINISHED => 'finished',
    ];

    protected $fillable = [
        'visa',
        'profession_id',
        'marketing_ratio',
        'gender',
        'details',
        'marketer_id',
        'country_id',
        'cv_id',
        'customer_id',
        'amount',
        'status',
        'destination',
        'arrival_airport',
        'date_arrival',
        'start_date',
        'ex_date',
        'user_id',
    ];
    public function getStatus(){
        // $cv = $this->cv;
        // return is_null($cv) ? null : self::STATUSES[$this->cv->pivot->status];
        return self::STATUSES[$this->status];
    }

    public function displayStatus(){
        // $status = '';
        // switch ($this->getStatus()) {
        //     case self::STATUS_WAITING:
        //         $status = 'في الانتظار';
        //         break;
        //     case self::STATUS_WORKING:
        //         $status = 'جاري';
        //         break;
        //     case self::STATUS_CANCELED:
        //         $status = 'ملغي';
        //         break;
        //     case self::STATUS_FINISHED:
        //         $status = 'منتهي';
        //         break;
        //     default:
        //         $status = 'غير معلومة';
        // }
        // return $status;
        return __('contracts.statuses.' . $this->getStatus());
    }

    public function getName(){
        return $this->customer->name;
    }

    public function getCurrency(){
        return 'ريال';
    }

    public function x_bail()
    {
        return $this->hasOne(Bail::class, 'x_contract_id');
    }

    public function bail()
    {
        return $this->hasOne(Bail::class, 'contract_id');
    }

    public function getMarketerMoney(){
        return $this->marketing_ratio;// * ($this->amount / 100);
    }
    public function getCustomerName(){
        if (is_null($this->customer)) {
            return 'لا يوجد';
        }

        return $this->customer->name;
    }
    public function getCvName(){
        if (is_null($this->cv)) {
            return 'لا يوجد';
        }

        return $this->cv->name;
    }
    public function getCvPassport(){
        if (is_null($this->cv)) {
            return 'لا يوجد';
        }

        return $this->cv->passport;
    }
    public function getOfficeName(){
        if (is_null($this->cv)) {
            return 'لا يوجد';
        }

        return $this->cv->office->name  ?? '';
    }
    public function getProfessionName(){
        if (is_null($this->cv)) {
            return 'لا يوجد';
        }

        return $this->cv->profession->name;
    }
    public function getApplicationDays($display = true, $remain_only = false){
        if ($remain_only) {
            $start_time = \Carbon\Carbon::parse($this->start_date);
            $now = \Carbon\Carbon::parse(date('Y-m-d'));
            // $now = now();
            $remain_days = $start_time->diffInDays($now, false);
            if ($start_time->gt($now)) {
                if ($display) {
                    return __('contracts.statuses.waiting');
                }
                return $this->ex_date - $remain_days;
            }
            if ($display) {
                return $this->ex_date - $remain_days . ' يوم';
            }
        }
        if ($display) {
            return $this->ex_date . ' يوم';
        }
        return $this->ex_date;
    }

    public function isWaiting(){
        return $this->checkStatus('waiting');
    }

    public function isWorking(){
        return $this->checkStatus('working');
    }

    public function isCanceled(){
        return $this->checkStatus('canceled');
    }

    public function isFinished(){
        return $this->checkStatus('finished');
    }

    public function checkStatus($status){
        if (gettype($status) == 'string') {
            return $status == self::STATUSES[$this->status];
        }
        elseif (gettype($status) == 'integer') {
            return $status == $this->status;
        }

        throw new \Exception("Unsupported status data type", 1);
    }

    public function marketer()
    {
        return $this->belongsTo('Modules\ExternalOffice\Models\Marketer');
    }

    public function office()
    {
        return $this->cv->belongsTo(Office::class);
    }

    public function country()
    {
        return $this->cv->belongsTo(Country::class);
    }

    public function profession()
    {
        return $this->cv->belongsTo(Profession::class);
    }

    public function cv()
    {
        return $this->belongsTo(Cv::class, 'cv_id');
    }

    public function getMarketerVoucherAttribute()
    {
        return $this->vouchers->where('marketer_id', $this->marketer_id)->first();
    }

    public function getAllVouchersAttribute()
    {
        $vouchers = Voucher::where('contract_id', $this->id)->get()
        ->merge(Voucher::where('voucherable_type', get_class($this))->where('voucherable_id', $this->id)->get());
        return $vouchers->unique();
    }

    public function getCvVouchersAttribute()
    {
        $cv = $this->cv;
        $voucher = $cv->voucher;
        $vouchers = new Collection();
        if ($voucher) {
            $vouchers->push($voucher);
        }

        return $vouchers;
    }

    // public function getVouchersAttribute()
    // {
    //     return $this->hasMany(Voucher::class);
    // }

    public function getBillsAttribute()
    {
        $bills = new Collection();
        foreach ($cv->cv_bills as $bill) {
            $bills->push($bill);
        }

        return $bills;
    }

    public function getPayedBillsAttribute()
    {
        $cv = $this->cv;
        $bills = new Collection();
        foreach ($cv->cv_bills as $bill) {
            if ($bill->bill->isPayed()) {
                $bills->push($bill);
            }
        }

        return $bills;
    }

    public function getCvsExpensesAttribute()
    {
        // return $this->payed_bills->sum('amount_in_riyal');
        // return $this->bills->sum('amount_in_riyal');

        $checked_payment_vouchers = $this->cv_vouchers->filter(function($voucher){
            return $voucher->statusIs('approved');
        });
        return $checked_payment_vouchers->sum('entry.amount');
    }

    public function getPaymentsAttribute()
    {
        $vouchers = Voucher::where('voucherable_type', get_class($this))
                    ->where('voucherable_id', $this->id)
                    ->where('type', Voucher::TYPE_PAYMENT)
                    ->get()->merge(Voucher::where('contract_id', $this->id)
                    ->where('type', Voucher::TYPE_PAYMENT)
                    ->get());
        return $vouchers;
    }

    public function getReceiptsAttribute()
    {
        $vouchers = Voucher::where('voucherable_type', get_class($this))
                    ->where('voucherable_id', $this->id)
                    ->where('type', Voucher::TYPE_RECEIPT)
                    ->get()->merge(Voucher::where('contract_id', $this->id)
                    ->where('type', Voucher::TYPE_RECEIPT)
                    ->get());
        return $vouchers;
    }

    public function getExpensesAttribute()
    {
        // return $this->payed_bills->sum('amount_in_riyal');
        // return $this->bills->sum('amount_in_riyal');
        $checked_payment_vouchers = $this->payments->filter(function($voucher){
            return $voucher->statusIs('approved');
        });
        return $checked_payment_vouchers->sum('entry.amount');
    }

    public function getNetAttribute()
    {
        return $this->amount - ($this->expenses);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function contractCustomer() {
        return $this->hasMany('Modules\Services\Models\ContractCustomer');
    }

    public function cancel(){
        $succeeded = $this->update(['status' => self::STATUS_CANCELED]);
        if($this->cv && $succeeded){
            $succeeded = $this->cv->cancel();
        }

        return $succeeded;
    }

    public function delete(){
        $result = parent::delete();
        return $result;
    }

    public static function statusFromString(string $status)
    {
        return array_search($status, self::STATUSES);
    }

    public static function statusFromNumber(int $status)
    {
        return self::STATUSES[$status];
    }

    public static function getByStatus($status, $from_date = null, $to_date = null){
        $status_in_string = self::statusFromString($status);
        $from_date = is_null($from_date) ? (is_null(Contract::first()) ? date('Y-m-d') : Contract::first()->created_at->format('Y-m-d')) : $from_date;
        $to_date = is_null($to_date) ? date('Y-m-d') : $to_date;
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $builder = static::where('status', $status_in_string);
        $builder->whereBetween('created_at', [$from_date_time, $to_date_time]);
        return $builder->get();
    }

    public static function initial($from_date = null, $to_date = null){
        return self::getByStatus('initial', $from_date, $to_date);
    }

    public static function waiting($from_date = null, $to_date = null){
        return self::getByStatus('waiting', $from_date, $to_date);
    }

    public static function working($from_date = null, $to_date = null){
        return self::getByStatus('working', $from_date, $to_date);
    }

    public static function canceled($from_date = null, $to_date = null){
        return self::getByStatus('canceled', $from_date, $to_date);
    }

    public static function finished($from_date = null, $to_date = null){
        return self::getByStatus('finished', $from_date, $to_date);
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
