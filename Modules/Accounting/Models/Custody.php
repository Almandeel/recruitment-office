<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Attachable;
use Modules\Accounting\Traits\{Yearable, Voucherable};
use Modules\Accounting\Models\Voucher;
use App\User;
use App\Traits\Statusable;
class Custody extends BaseModel
{
    use Attachable;
    use Yearable;
    use Voucherable;
    public const STATUS_OPEN    = 1;
    public const STATUS_CLOSED   = 2;
    public const STATUSES = [
    self::STATUS_OPEN => 'open',
    self::STATUS_CLOSED => 'closed',
    ];
    protected $table = 'custodies';
    protected $fillable = ['amount', 'details', 'user_id', 'year_id'];
    /**
    * The attributes that should be cast.
    *
    * @var array
    */
    protected $casts = [
    'created_at' => 'datetime:Y/m/d',
    'updated_at' => 'datetime:Y/m/d',
    ];
    
    
    public function getVoucherAttribute()
    {
        $voucher = new Voucher();
        $voucher->id = 0;
        $voucher->amount = 0;
        $last = $this->vouchers->where('type', Voucher::TYPE_PAYMENT)->last();
        if ($last) {
            $voucher = $last;
        }

        return $voucher;
    }
    
    public function getEntryAttribute()
    {
        $voucher = $this->voucher;
        if ($voucher) {
            $entry = $voucher->entry;
            if ($entry) {
                return $entry;
            }
        }
    }
    
    public function getAccountAttribute()
    {
        $account = new Account();
        $account->id = 0;
        $account->name = 'لا يوجد';
        
        $entry = $this->entry;
        if ($entry) {
            $last = $entry->debts()->last();
            if ($last) {
                $account = $last;
            }
        }
        return $account;
    }
    
    public function getDebtAccountAttribute()
    {
        $account = new Account();
        $account->id = 0;
        $account->name = 'لا يوجد';

        $entry = $this->entry;
        if ($entry) {
            $last = $entry->debts()->last();
            if ($last) {
                $account = $last;
            }
        }
        return $account;
    }
    
    public function getCreditAccountAttribute()
    {
        $account = new Account();
        $account->id = 0;
        $account->name = 'لا يوجد';

        $entry = $this->entry;
        if ($entry) {
            $last = $entry->credits()->last();
            if ($last) {
                $account = $last;
            }
        }
        return $account;
    }
    
    public function getFormatedAmountAttribute(){
        return number_format($this->voucher->amount, 2);
    }
    
    public function getName(){
        if ($this->account) {
            return $this->account->name;
        }
    }
    
    public function getStatus($get_value = true){
        $status = ($this->remain() == 0) ? self::STATUS_CLOSED : self::STATUS_OPEN;
        if ($get_value) {
            return $status;
        }
        
        return self::STATUSES[$status];
    }
    
    public function register($amount, $debt_account, $credit_account, $details = null)
    {
        $voucher = $this->vouch([
        'amount' => $amount,
        'type' => Voucher::TYPE_PAYMENT,
        'details' => $details,
        'voucher_date' => $date = date('Y-m-d'),
        'status' => Statusable::$STATUS_CHECKED,
        ]);
        
        $entry = Entry::create([
        'type' => Entry::TYPE_JOURNAL,
        'amount' => $amount,
        'details' => $details,
        'entry_date' => $date,
        ]);
        $entry->accounts()->attach($debt_account, [
        'amount' => $amount,
        'side' => Entry::SIDE_DEBTS,
        ]);
        $entry->accounts()->attach($credit_account, [
        'amount' => $amount,
        'side' => Entry::SIDE_CREDITS,
        ]);
        $voucher->entry()->save($entry);
        
        $this->vouchers()->save($voucher);
        return $voucher;
    }
    
    public function consum($amount, $debt_account, $details = null)
    {
        if ($this->voucher) {
            $voucher = $this->vouch([
            'amount' => $amount,
            'type' => Voucher::TYPE_RECEIPT,
            'details' => $details,
            'voucher_date' => $date = date('Y-m-d'),
            'status' => Statusable::$STATUS_CHECKED,
            ]);
            
            $entry = Entry::create([
            'type' => Entry::TYPE_ADJUST,
            'amount' => $amount,
            'details' => $details,
            'entry_date' => $date,
            ]);
            if (is_array($debt_account)) {
                $accounts = $debt_account[0];
                $amounts = $debt_account[1];
                for ($i=0; $i < count($accounts); $i++) {
                    $account_id = $accounts[$i];
                    $account_amount = $amounts[$i];
                    $entry->accounts()->attach($account_id, [
                        'amount' => $account_amount,
                        'side' => Entry::SIDE_DEBTS,
                    ]);
                }
            }else{
                $entry->accounts()->attach($debt_account, [
                'amount' => $amount,
                'side' => Entry::SIDE_DEBTS,
                ]);
            }
            $entry->accounts()->attach($this->debt_account->id, [
            'amount' => $amount,
            'side' => Entry::SIDE_CREDITS,
            ]);
            $voucher->entry()->save($entry);
            
            // $voucher->register([
            //     'entry' => [
            //         'type' => Entry::TYPE_ADJUST,
            //         'amount' => $amount,
            //         'details' => $details,
            //         'entry_date' => $date,
            //     ],
            //     'debts' => [['account' => $debt_account, 'amount' => $amount]],
            //     'credits' => [['account' => $this->debt_account->id, 'amount' => $amount]],
            // ]);
            
            $this->vouchers()->save($voucher);
            return $voucher;
        }
    }
    
    public function checkStatus($status){
        if (gettype($status) == 'string') {
            return $status == $this->getStatus(false);
        }
        elseif (gettype($status) == 'integer') {
            return $status == $this->getStatus();
        }
        
        throw new \Exception("Unsupported status data type", 1);
    }
    
    public function displayStatus(){
        return __('custodies.statuses.' . $this->getStatus(false));
    }
    
    public function payments(){
        $vouchers = $this->vouchers;
        if ($vouchers->count() > 1) {
            $vouchers->forget(0);
            return $vouchers;
        }
    }
    public function isPayed(){
        return $this->remain() <= 0;
    }
    public function payed($formated = false)
    {
        $payed = 0;
        if($this->vouchers->count() > 1){
            $payed = $this->payments()->sum('amount');
        }
        
        if ($formated) {
            return number_format($payed, 2);
        }
        return $payed;
    }
    
    public function remain($formated = false)
    {
        // dd($this->amount, $this->payed());
        $remain = (float) $this->amount - $this->payed();
        if ($formated) {
            return number_format($remain, 2);
        }
        return $remain;
    }
    
    public function isVouched()
    {
        return $this->vouchers->count() > 0;
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function pay(array $attributes){
        if (!(array_key_exists('type', $attributes))) {
            $attributes['type'] = Voucher::TYPE_RECEIPT;
        }
        if (!(array_key_exists('details', $attributes))) {
            $attributes['details'] = 'عبارة عن سداد تسوية من العهدة رقم: ' . $this->id;
        }
        
        return $this->vouch($attributes);
    }
    
    public static function create(array $attributes){
        if (!(array_key_exists('user_id', $attributes))) {
            $attributes['user_id'] = auth()->user()->getKey();
        }
        // if (array_key_exists('amount', $attributes)) {
        //     $amount = $attributes['amount'];
        //     $amount_type = gettype($amount);
        //     if ($amount_type == 'array') {
        //         $attributes['amount'] = json_encode(['value' => $amount[0], 'currency' => $amount[1]]);
        //     }elseif ($amount_type == 'string') {
        //         if (is_json($amount)) {
        //             $attributes['amount'] = $amount;
        //         }else{
        //             $attributes['amount'] = json_encode(['value' => $amount, 'currency' => $attributes['currency']]);
        //         }
        //     }
        //     else{
        //         if (array_key_exists('currency', $attributes)) {
        //             $attributes['amount'] = json_encode(['value' => $amount, 'currency' => $attributes['currency']]);
        //         }
        //     }
        // }
        $custody = static::query()->create($attributes);
        // dd($attributes, $custody);
        // $custody->vouch([
        //     'amount' => $custody->amount,
        //     // 'currency' => $custody->amount->currency,
        //     'details' => $custody->details,
        //     'type' => Voucher::TYPE_PAYMENT,
        //     'voucher_date' => date("Y-m-d"),
        // ]);
        return $custody;
    }
    
    public function update(array $attributes = [], array $options = [])
    {
        // dd($attributes);
        $result = parent::update($attributes, $options);
        
        return $result;
    }
    
    public function delete(){
        $result = parent::delete();
        
        return $result;
    }
}