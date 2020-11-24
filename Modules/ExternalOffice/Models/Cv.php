<?php

namespace Modules\ExternalOffice\Models;

use Exception;
use Carbon\Carbon;
use App\Traits\Attachable;
use Modules\Services\Models\Contract;
use Modules\Accounting\Models\Voucher;
use Illuminate\Database\Eloquent\Model;
use Modules\ExternalOffice\Scopes\OfficeScope;

class Cv extends BaseModel
{
    use Attachable;
    public const STATUS_WAITING = 0;
    public const STATUS_ACCEPTED = 1;
    public const STATUS_CONTRACTED = 2;
    public const STATUS_PULLED = 3;
    public const STATUS_RETURNED = 4;
    public const STATUS_CANCELED = 4;

    public const STATUSES = [
    self::STATUS_WAITING => 'waiting',
    self::STATUS_ACCEPTED => 'accepted',
    self::STATUS_CONTRACTED => 'contracted',
    self::STATUS_PULLED => 'pulled',
    self::STATUS_RETURNED => 'returned',
    self::STATUS_CANCELED => 'canceled',
    ];

    public const STATUSES_CLASSES = [
    self::STATUS_WAITING => 'secondary',
    self::STATUS_ACCEPTED => 'info',
    self::STATUS_CONTRACTED => 'success',
    self::STATUS_PULLED => 'warning',
    self::STATUS_RETURNED => 'danger',
    self::STATUS_CANCELED => 'danger',
    ];

    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;
    public const GENDERS = [
    self::GENDER_MALE => 'male',
    self::GENDER_FEMALE => 'female',
    ];


    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
    'name',
    'passport',
    'accepted',
    'status',
    'amount',
    'procedure',
    'gender',
    'birth_date',
    'billed',
    'contract_id',
    'user_id',
    'country_id',
    'office_id',
    'profession_id',
    'nationality',
    'religion',
    'children',
    'phone',
    'qualification',
    'english_speaking_level',
    'experince',
    'weight',
    'height',
    'sewing',
    'decor',
    'cleaning',
    'washing',
    'cooking',
    'babysitting',
    'passport_place_of_issue',
    'passport_issuing_date',
    'passport_expiration_date',
    'contract_period',
    'contract_salary',
    'bio',
    'photo',
    'passport_photo',
    'marital_status',
    'birthplace',
    'reference_number',
    ];

    public function age()
    {
        if ($this->birth_date) {
            $birth_date_time = Carbon::parse($this->birth_date);
            return $birth_date_time->diffInYears(now());
        }
        return null;
    }

    public function payed()
    {
        $bills = $this->bills(true);
        // $amounts = 0;
        // foreach ($bills as $bill) {
        //     $amounts += $bill->pivot->amount;
        // }

        // return $amounts;

        return $bills->sum('pivot.amount');
    }
    public function getGender(){ return $this->gender; }
    public function displayGender(){ return __('global.gender_' . self::GENDERS[$this->getGender()]); }
    public function remain()
    {
        return ($this->amount - $this->payed());
    }

    public function displayStatus($style = 'badge')
    {
        $classes = $style == 'badge' ? 'badge badge-' : 'text';
        $classes .= $this->getStatus('class');
        $builder = "<span class='$classes'>";
        $builder .=  __('cvs.statuses.' . $this->getStatus('name'));
        $builder .= "</span>";

        return $builder;
    }

    public function isDeleteable(){
        return $this->isWaiting();
        // return !($this->isAccepted() || $this->isContracted());
    }

    public function isEditable(){
        return !($this->isPulled());
    }

    public function isWaiting()
    {
        return $this->checkStatus(self::STATUS_WAITING);
    }

    public function isAccepted()
    {
        return $this->checkStatus(self::STATUS_ACCEPTED);
    }

    public function isContracted()
    {
        return $this->checkStatus(self::STATUS_CONTRACTED);
    }

    public function isPulled()
    {
        return $this->checkStatus(self::STATUS_PULLED);
    }

    public function isReturned()
    {
        return $this->checkStatus(self::STATUS_RETURNED);
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
    public function getStatus($data = 'value'){
        if ($data == 'name') {
            return self::STATUSES[$this->status];
        }
        elseif ($data == 'class') {
            return self::STATUSES_CLASSES[$this->status];
        }
        return $this->status;
    }
    public function cancel(){
        return $this->update([
        'contract_id' => null,
        'status' => self::STATUS_ACCEPTED,
        ]);
    }

    public function isPayed()
    {
        return $this->remain() == 0;
    }

    public function bills($payed_only = false)
    {
        if ($payed_only) {
            return $this->bills->filter(function ($bill) {
                return $bill->isPayed();
            });
        }
        return $this->belongsToMany(Bill::class)->withPivot(['amount']);
    }

    public function returned()
    {
        return $this->hasOne(Returned::class);
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }

    public function contract()
    {
        return $this->contracts->where('pivot.status', Contract::STATUS_WORKING)->sortByDesc('pivot.updated_at')->first();
        // return $this->belongsTo('Modules\Services\Models\Contract');
    }

    public function getContractAttribute()
    {
        return $this->contracts->where('pivot.status', Contract::STATUS_WORKING)->sortByDesc('pivot.updated_at')->first();

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pulling()
    {
        $pull = Pull::create([
        'cv_id' => $this->id,
        'user_id' => auth()->user()->getKey(),
        ]);
        // $this->update(['status' => self::STATUS_PULLED]);
        return $pull;
    }

    public function pull()
    {
        return $this->hasOne(Pull::class);
    }

    public function returns()
    {
        return $this->hasOne(Returns::class);
    }

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function scopeNotBilled($query)
    {
        return $query->where('billed', false);
    }

    public function cv_bills()
    {
        return $this->hasMany('Modules\ExternalOffice\Models\BillCv');
    }

    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'cv_id');
    }


    public function office()
    {
        return $this->belongsTo('Modules\Main\Models\Office');
    }

    public function contracting($contract_id, $status = Contract::STATUS_WAITING){
        $this->update(['status' => Cv::STATUS_CONTRACTED]);
        $this->contracts()->attach($contract_id, [
        'status' => $status ?? Contract::STATUS_WAITING,
        ]);
    }


    public function contracts()
    {
        return $this->belongsToMany(Contract::class)->withPivot(['status', 'created_at', 'updated_at']);
    }

    public function billPivot($bill_id)
    {
        return BillCv::where('cv_id', $this->id)->where('bill_id', $bill_id)->first();
    }

    public function delete(){
        $this->contracts()->detach();
        $result = parent::delete();
        return $result;
    }


    public static function boot()
    {
        static::addGlobalScope(new OfficeScope());

        parent::boot();
    }

    public static function getWaiting(array $parameters = []){
        return static::getByStatus(Cv::STATUS_WAITING, $parameters);
    }

    public static function getAccepted(array $parameters = []){
        return static::getByStatus(Cv::STATUS_ACCEPTED, $parameters);
    }

    public static function getContracted(array $parameters = []){
        return static::getByStatus(Cv::STATUS_CONTRACTED, $parameters);
    }

    public static function getPulled(array $parameters = []){
        return static::getByStatus(Cv::STATUS_PULLED, $parameters);
    }

    public static function getCanceled(array $parameters = []){
        return static::getByStatus(Cv::STATUS_CANCELED, $parameters);
    }

    public static function getByStatus($status, array $parameters = []){
        $cvs = static::where('status', $status);
        foreach ($parameters as $param => $value) {
            $cvs->where($param, $value);
        }
        return $cvs->get();
    }

    public function flgihts()
    {
        return $this->hasMany(CvFlight::class);
    }

    public function getLastFlightAttribute()
    {
        return $this->flgihts()->latest()->first();
    }
}
