<?php

namespace Modules\Accounting\Models;



// use Modules\Accounting\Traits\Authable;
use Modules\Accounting\Traits\Yearable;
use Modules\Accounting\Traits\Safeable;
use App\Traits\{Attachable, Backupable};
use App\Traits\Statusable;
use Modules\ExternalOffice\Models\{Bill, Cv, Advance};
use Modules\Services\Models\{Marketer, Contract};

class Voucher extends BaseModel
{
    use Safeable;
    use Yearable;
    use Statusable;
    use Attachable, Backupable;
    
    
    public const TYPE_RECEIPT        =  1;
    public const TYPE_PAYMENT        = -1;
    public const TYPES        = [
    self::TYPE_RECEIPT,
    self::TYPE_PAYMENT,
    ];
    
    public function voucherable()
    {
        return $this->morphTo();
    }
    
    
    public function advance()
    {
        return $this->belongsTo(Advance::class);
    }
    
    
    public function bill()
    {
        if($this->voucherable_type == Bill::class){
            return $this->voucherable;
        }
    }
    
    
    public function getBillAttribute()
    {
        if($this->voucherable_type == Bill::class){
            return $this->voucherable;
        }
    }
    
    
    // public function entry()
    // {
    //     return $this->morphOne(Entry::class, 'entryable');
    // }
    
    protected $fillable = ['id', 'type', 'number', 'amount',	'details',	'status', 'currency', 'voucher_date', 'voucherable_type', 'voucherable_id', 'advance_id', 'cv_id', 'contract_id', 'marketer_id', 'year_id'];
    
    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function cv(){
        return $this->belongsTo(Cv::class);
    }

    public function contract(){
        return $this->belongsTo(Contract::class);
    }

    public function marketer(){
        return $this->belongsTo(Marketer::class);
    }
    
    public static function getStaticType($type){
        return __('accounting::vouchers.types.' . $type);
    }
    
    public function getType(){
        return __('accounting::vouchers.types.' . $this->type);
    }
    
    public function displayType(){
        return __('accounting::vouchers.types.' . $this->type);
    }
    
    public function displayAmount($with_currency = true){
        $amount = number_format($this->amount, 2);
        if($with_currency){
            $amount .= ' ' . $this->currency;
        }
        return $amount;
    }
    
    public function benefitIsModel(){
        $dir = 'Modules\\';
        if (class_exists($this->voucherable_type)) {
            return substr( $this->voucherable_type, 0, strlen($dir)) === $dir;
        }

        return false;
    }
    
    public function isReceipt(){
        return $this->type == self::TYPE_RECEIPT;
    }
    
    public function isPayment(){
        return $this->type == self::TYPE_PAYMENT;
    }
    
    public function isEditable(){
        return is_null($this->entry);
    }
    
    public function isChecked(){
        return !is_null($this->entry);
    }
    
    public function typeIs($type){
        if(gettype($type) == 'string'){
            if ($type == 'payment') {
                return $this->isPayment();
            }
            elseif ($type == 'receipt') {
                return $this->isReceipt();
            }
        }
        else if(gettype($type) == 'integer'){
            return $this->type == $type;
        }
        
        return false;
    }
    
    public function getBenefit(){
        $benefit = $this->entry ? $this->entry->debts()->first()->name : 'غير معروف';
        if($this->advance){
            $benefit = 'مكتب ' . $this->advance->office->name;
        }
        elseif($this->bill()){
            $benefit = 'مكتب ' . $this->bill()->office->name;
        }
        elseif($this->marketer) {
            $benefit = $this->marketer->name;
        }
        elseif($this->benefitIsModel()) {
            if($this->voucherable){
                $benefit = $this->voucherable->getName();
            }
        }
        else{
            $benefit = $this->voucherable_type;
        }

        return $benefit;
    }
    
    public static function create(array $attributes = [])
    {
        if (!array_key_exists('voucher_date', $attributes)) {
            $attributes['voucher_date'] = date('Y-m-d');
        }
        // if (!array_key_exists('user_id', $attributes)) {
        //     $attributes['user_id'] = auth()->user()->id;
        // }
        $model = static::query()->create($attributes);
        
        return $model;
    }
    
    public function update(array $attributes = [], array $options = [])
    {
        
        $result = parent::update($attributes, $options);
        if($this->entry && array_key_exists('amount', $attributes)) $this->entry->update(['amount' => $attributes['amount']]);
        if(!is_null($this->advance)){
            if ($this->advance->amount !== $this->amount && $this->id == $this->advance->voucher()->id) {
                $this->advance->update(['amount' => $this->amount]);
            }
        }
        return $result;
    }
    
    public function register(array $data){
        if (array_key_exists('entry', $data) && array_key_exists('debts', $data) && array_key_exists('credits', $data)) {
            $entry = Entry::create($data['entry']);
            $this->entry()->save($entry);
            foreach ($data['debts'] as $debt) {
                $entry->accounts()->attach($debt['account'], [
                    'amount' => $debt['amount'],
                    'side' => Entry::SIDE_DEBTS,
                ]);
            }
            foreach ($data['credits'] as $credit) {
                $entry->accounts()->attach($credit['account'], [
                    'amount' => $debt['amount'],
                    'side' => Entry::SIDE_CREDITS,
                ]);
            }

            return $entry;
        }
        elseif (array_key_exists('debts_accounts', $data) && array_key_exists('credits_accounts', $data) && array_key_exists('debts_amounts', $data) && array_key_exists('credits_amounts', $data)) {
            if (array_key_exists('entry', $data)){
                $entry = Entry::create($data['entry']);
            }else{
                $entry = Entry::create(['entry_date' => date('Y-m-d'), 'details' => $this->details, 'amount' => array_sum($data['debts_amounts'])]);
            }

            if (array_key_exists('debts_accounts', $data) && array_key_exists('debts_amounts', $data)){
                for ($i=0; $i < count($data['debts_accounts']); $i++) {
                    $account = $data['debts_accounts'][$i];
                    $amount = $data['debts_amounts'][$i];
                    $entry->accounts()->attach($account, [
                        'amount' => $amount,
                        'side' => Entry::SIDE_DEBTS,
                    ]);
                }
            }
            
            if (array_key_exists('credits_accounts', $data) && array_key_exists('credits_amounts', $data)){
                for ($i=0; $i < count($data['credits_accounts']); $i++) {
                    $account = $data['credits_accounts'][$i];
                    $amount = $data['credits_amounts'][$i];
                    $entry->accounts()->attach($account, [
                        'amount' => $amount,
                        'side' => Entry::SIDE_CREDITS,
                    ]);
                }
            }
            $this->entry()->save($entry);
            return $entry;
        }
    }

    public function delete()
    {
        $entry = $this->entry;
        $result = parent::delete();
        if ($result) {
            if ($entry) {
                $entry->delete();
            }
        }

        return $result;
    }
    
    
    public static function receipts(){
        return self::where('type', self::TYPE_RECEIPT)->get();
    }
    
    
    public static function payments(){
        return self::where('type', self::TYPE_PAYMENT)->get();
    }
    
    // public static function boot(){
    //     parent::boot();
    //     static::created(function($voucher){
    
    //     });
    // }
    
    
    public function backupData(){
        $data = $this->toArray();
        if ($this->auth()) {
            $data['auth'] = $this->auth()->toArray();
        }
        
        return $data;
    }
    
    public function export(){
        $data = $this->toArray();
        if ($this->attachments) {
            $data['attachments'] = $this->attachments->toArray();
        }
        if ($this->auth()) {
            $data['auth'] = $this->auth()->toArray();
        }
        
        return $data;
    }
    
    public static function exports($vouchers){
        $data = [];
        foreach ($vouchers as $voucher) {
            $data['vouchers'][] = $voucher->export();
        }
        return $data;
    }
}