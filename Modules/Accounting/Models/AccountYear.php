<?php

namespace Modules\Accounting\Models;



class AccountYear extends BaseModel
{
    public const SIDE_CREDIT   = 1;
    public const SIDE_DEBT     = -1;
    public const TYPE_CLOSE    = 0;
    public const TYPE_OTHER    = 1;
    public const TYPES     = [
        self::TYPE_CLOSE,
        self::TYPE_OTHER,
    ];
    protected $table = 'account_year';
    protected $fillable = ['entry_side', 'type', 'year_id', 'account_id'];

    public function account(){
        return $this->belongsTo('App\Account', 'account_id');
    }
    public function year(){
        return $this->belongsTo('App\Year', 'year_id');
    }
}
