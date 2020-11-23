<?php

namespace Modules\Accounting\Models;


use Modules\Accounting\Traits\Authable;
use Modules\Accounting\Traits\Yearable;
use App\Traits\Logable;

class Entry extends BaseModel
{
    use Yearable;
    
    public const TYPE_JOURNAL   = 1;
    public const TYPE_ADJUST    = 2;
    public const TYPE_ADVERSE   = 3;
    public const TYPE_CLOSE     = 4;
    public const TYPE_DOUBLE    = 5;
    public const TYPE_USE       = 6;
    public const TYPE_OPEN     = 7;
    public const TYPE_OTHER     = 8;
    
    public const TYPES = [
    self::TYPE_JOURNAL,
    self::TYPE_ADJUST,
    self::TYPE_ADVERSE,
    self::TYPE_CLOSE,
    self::TYPE_DOUBLE,
    self::TYPE_USE,
    self::TYPE_OPEN,
    self::TYPE_OTHER,
    ];
    
    public const SIDE_DEBTS   = 1;
    public const SIDE_CREDITS = 2;
    public const SIDES = [
    self::SIDE_DEBTS,
    self::SIDE_CREDITS,
    ];
    
    protected $table = 'entries';
    // protected $primaryKey = 'pk';
    protected $fillable = ['id', 'amount', 'details', 'entry_date', 'type', 'entry_id', 'year_id'];
    
    public function getDate($format = 'Y/m/d'){
        if($this->entry_date){
            return date($format, strtotime($this->entry_date . ' 00:00:00'));
        }else{
            return $this->created_at->format($format);
        }
    }
    public function entryable()
    {
        return $this->morphTo(__FUNCTION__, 'entryable_type', 'entryable_id');
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function isAdverse(){
        return $this->typeIs(self::TYPE_ADVERSE);
    }
    
    public function typeIs($type){
        return $this->type == $type;
    }
    
    public function hasAdverse(){
        return !$this->isAdverse() && $this->adverse() != null;
    }
    
    public function adverse($create = false){
        if($create){
            $details = str_replace('__entry_id__', $this->id, __('accounting::entries.details_adverse'));
            $adverse = self::create([
            'amount' => $this->amount,
            'details' => $details,
            'entry_id' => $this->id,
            'entry_date' => date('Y-m-d'),
            'type' => self::TYPE_ADVERSE,
            ]);
            
            foreach ($this->debts() as $debt) {
                $adverse->accounts()->attach($debt->id, [
                'amount' => $debt->pivot->amount,
                'side' => self::SIDE_CREDITS,
                ]);
            }
            
            foreach ($this->credits() as $credit) {
                $adverse->accounts()->attach($credit->id, [
                'amount' => $credit->pivot->amount,
                'side' => self::SIDE_DEBTS,
                ]);
            }
            // dd($adverse);
            
            return $adverse;
        }
        if($this->type == self::TYPE_ADVERSE){
            return null;
        }
        
        return self::where('entry_id', $this->id)->where('type', self::TYPE_ADVERSE)->get()->first();
    }
    
    
    public function accounts($side = null){
        if($side != null){
            return $this->accounts->filter(function($account) use ($side){
                return $account->pivot->side == $side;
            });
        }
        return $this->belongsToMany(Account::class)->withPivot('amount', 'side');
    }
    
    public function amounts(){
        return $this->hasMany(AccountEntry::class, 'entry_id')->where('year_id', $this->year_id);
    }
    
    public function debts(){ return $this->accounts(self::SIDE_DEBTS); }
    public function credits(){ return $this->accounts(self::SIDE_CREDITS); }
    
    
    public function salary(){
        return $this->hasOne(Salary::class, 'entry_id');
    }
    
    public function expense(){
        return $this->hasOne(Expense::class, 'entry_id');
    }
    
    public function payment(){
        return $this->hasOne(Payment::class, 'entry_id');
    }
    
    public function transfer(){
        return $this->hasOne(Transfer::class, 'entry_id');
    }
    
    public function cheque(){
        return $this->hasOne(Cheque::class, 'entry_id');
    }
    
    public static function create(array $attributes = [])
    {
        // $attributes['year_id'] = yearId();
        // $attributes['user_id'] = auth()->user()->getKey();
        // $attributes['id'] = year()->generateEntryId();
        if (!array_key_exists('entry_date', $attributes)) {
            $attributes['entry_date'] = date('Y-m-d');
        }
        $model = static::query()->create($attributes);
        return $model;
    }
    
    public function delete()
    {
        // \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // if($this->salary) $this->salary->delete();
        // if($this->expense) $this->expense->delete();
        // if($this->payment) $this->payment->delete();
        // if($this->transfer) $this->transfer->delete();
        // if($this->cheque) $this->cheque->delete();
        // if($this->entryable) $this->entryable->delete();
        // \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $entryable = $this->entryable;
        $adverse = $this->adverse();
        $result = parent::delete();
        if($result){
            if($adverse) $adverse->delete();
            // if($entryable) $entryable->delete();
            $this->accounts()->detach();
        }
        return $result;
    }

    public function backupData(){
        $data = $this->toArray();

        return $data;
    }
}