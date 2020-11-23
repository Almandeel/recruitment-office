<?php

namespace Modules\Accounting\Models;



class AccountEntry extends BaseModel
{
    public const TYPE_CREDIT   = 1;
    public const TYPE_DEBT     = -1;
    protected $table = 'account_entry';
    protected $fillable = ['amount', 'side', 'currency_id', 'year_id', 'account_id', 'entry_id'];

    public function account(){
        return $this->belongsTo('App\Account', 'account_id');
    }
    public function entry(){
        return $this->belongsTo('App\Entry', 'entry_id');
    }
    public function currency(){
        return $this->belongsTo('App\Currency', 'currency_id');
    }

    public function bill(){
        return $this->belongsTo('App\Bill');
    }

    public function invoice(){
        return $this->belongsTo('App\Invoice');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    
    public function toAccount(){
        return $this->belongsTo('App\Account', 'to');
    }
    
    public function fromAccount(){
        return $this->belongsTo('App\Account', 'from');
    }
    
    public function branch() {
        return $this->belongsTo('App\Branch', 'branch_id');
    }
    public static function create(array $attributes = [])
    {
        
        $attributes['year_id'] = yearId();
        $attributes['currency_id'] = year()->currency->id;
        $model = static::query()->create($attributes);
        return $model;
    }
}
