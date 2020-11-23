<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Collection;
class Center extends BaseModel
{
    public const TYPE_COST         = 0;
    public const TYPE_PROFIT           = 1;
    public const TYPES = [
    self::TYPE_COST,
    self::TYPE_PROFIT,
    ];

    protected $fillable = [
    'id', 'name', 'type'
    ];
    public function displayName(){
        return $this->getType() . ': ' . $this->name;
    }
    public function getType(){
        return __('accounting::centers.types.' . $this->type);
    }

    public function balance($formatted = false){
        $balance = 0;
        return $formatted ? number_format($balance, 2) : $balance;
    }

    public function isCost(){
        return $this->type == self::TYPE_COST;
    }

    public function isProfit(){
        return $this->type == self::TYPE_PROFIT;
    }

    public static function costs(){
        return self::where('type', self::TYPE_COST)->get();
    }

    public static function profits(){
        return self::where('type', self::TYPE_PROFIT)->get();
    }

    public function accounts()
    {
        return  $this->belongsToMany(Account::class, 'account_center');
    }


    public function entries($year_id = null)
    {
        $year_id = is_null($year_id) ? (!is_null(year()) ? yearId() : null) : $year_id;
        $entries = new Collection();
        foreach ($this->accounts as $account) {
            $newEntries = $this->isProfit() ? $account->credits($year_id) : $account->debts($year_id);
            $entries = $entries->merge($newEntries);
        }
        return $entries;
    }

    public function update(array $attributes = [], array $options = []){
        return parent::update($attributes, $options);
    }

    public static function create(array $attributes = []){
        $account = static::query()->create($attributes);
        return $account;
    }

    public function delete(){
        $this->accounts()->detach();
        $result =  parent::delete();
        return $result;
    }
}
