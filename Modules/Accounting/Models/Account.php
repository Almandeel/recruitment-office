<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Collection;
use Modules\Accounting\Traits\AccountTrait;
class Account extends BaseModel
{
    use AccountTrait;
    /*
    * Account Types
    */
    public const TYPE_CAPITAL           = 0;
    public const TYPE_SAFE              = 1;
    public const TYPE_BANK              = 2;
    public const TYPE_SALE              = 3;
    public const TYPE_PURCHASE          = 4;
    public const TYPE_SALE_RETURN       = 5;
    public const TYPE_PURCHASE_RETURN   = 6;
    public const TYPE_DEBT              = 7;
    public const TYPE_CREDIT            = 8;
    public const TYPE_EXPENSE           = 9;
    public const TYPE_INCOME            = 10;
    public const TYPE_REVENUE           = 10;
    public const TYPE_RECEIVABLE_NOTE   = 11;
    public const TYPE_PAYABLE_NOTE      = 12;
    public const TYPE_STORE = 13;
    
    public const TYPE_PRIMARY           = 1;
    public const TYPE_SECONDARY         = 0;
    public const TYPES = [
    self::TYPE_SECONDARY => 'حساب فرعي',
    self::TYPE_PRIMARY => 'حساب رئيسي',
    ];
    
    public const SIDE_DEBT = 0;
    public const SIDE_CREDIT = 1;
    public const SIDES = [
    self::SIDE_DEBT => 'مدين',
    self::SIDE_CREDIT => 'دائن',
    ];
    
    
    public const ACCOUNT_ASSETS = 1;
    public const ACCOUNT_FIXED_ASSETS = 11;
    public const ACCOUNT_CURRENT_ASSETS = 12;
    
    public const ACCOUNT_SAFES = 121;
    public const ACCOUNT_CASHES = 1211;
    public const ACCOUNT_BANKS = 1212;
    
    public const ACCOUNT_CUSTOMERS = 122;
    public const ACCOUNT_EMPLOYEES = 123;
    public const ACCOUNT_EXTERNAL_OFFICES = 124;
    
    public const ACCOUNT_LIABILITIES = 2;
    
    public const ACCOUNT_OWNERS = 3;
    public const ACCOUNT_CAPITAL = 31;
    
    public const ACCOUNT_EXPENSES = 4;
    public const ACCOUNT_EXPENSE = 41;
    
    public const ACCOUNT_REVENUES = 5;
    public const ACCOUNT_REVENUE = 51;
    public const ACCOUNT_FINALS = 6;
    
    public const DEFAULTS = [
    self::ACCOUNT_ASSETS,
    self::ACCOUNT_FIXED_ASSETS,
    self::ACCOUNT_CURRENT_ASSETS,
    self::ACCOUNT_SAFES,
    self::ACCOUNT_CASHES,
    self::ACCOUNT_BANKS,
    self::ACCOUNT_CUSTOMERS,
    self::ACCOUNT_EMPLOYEES,
    self::ACCOUNT_EXTERNAL_OFFICES,
    self::ACCOUNT_LIABILITIES,
    self::ACCOUNT_OWNERS,
    self::ACCOUNT_CAPITAL,
    self::ACCOUNT_EXPENSES,
    self::ACCOUNT_REVENUES,
    self::ACCOUNT_FINALS,
    ];
    
    protected $table = 'accounts';
    protected $fillable = [
    'id', 'name', 'type', 'number', 'side', 'main_account','final_account','accountable_id', 'accountable_type'
    ];
    
    
    public function accountable()
    {
        return $this->morphTo(__FUNCTION__, 'accountable_type', 'accountable_id');
    }
    
    public static function assets() { return self::find(self::ACCOUNT_ASSETS); }
    public static function fixedAssets() { return self::find(self::ACCOUNT_FIXED_ASSETS); }
    public static function currentAssets() { return self::find(self::ACCOUNT_CURRENT_ASSETS); }
    public static function safes() { return self::find(self::ACCOUNT_SAFES); }
    public static function cashes() { return self::find(self::ACCOUNT_CASHES); }
    public static function banks() { return self::find(self::ACCOUNT_BANKS); }
    public static function customers() { return self::find(self::ACCOUNT_CUSTOMERS); }
    public static function employees() {
        $employees = self::find(self::ACCOUNT_EMPLOYEES); 
        if ($employees) {
            return $employees;
        }
        return self::create([
            'id' => self::ACCOUNT_EMPLOYEES,
            'number' => self::ACCOUNT_EMPLOYEES,
            'name' => 'الموظفين',
            'main_account' => self::ACCOUNT_CURRENT_ASSETS,
            'type' => self::TYPE_PRIMARY,
        ]);
    }
    public static function externalOffices() {
        $external_offices = self::find(self::ACCOUNT_EXTERNAL_OFFICES); 
        if ($external_offices) {
            return $external_offices;
        }
        return self::create([
            'id' => self::ACCOUNT_EXTERNAL_OFFICES,
            'number' => self::ACCOUNT_EXTERNAL_OFFICES,
            'name' => 'المكاتب الخارجية',
            'main_account' => self::ACCOUNT_CURRENT_ASSETS,
            'type' => self::TYPE_PRIMARY,
        ]);
    }
    public static function liabilities() { return self::find(self::ACCOUNT_LIABILITIES); }
    public static function owners() { return self::find(self::ACCOUNT_OWNERS); }
    public static function capital() { return self::find(self::ACCOUNT_CAPITAL); }
    public static function expenses() { return self::find(self::ACCOUNT_EXPENSES); }
    public static function expense() { return self::find(self::ACCOUNT_EXPENSE); }
    
    public static function revenues() { return self::find(self::ACCOUNT_REVENUES); }
    public static function revenue() { return self::find(self::ACCOUNT_REVENUE); }
    public static function finals() { return self::find(self::ACCOUNT_FINALS); }
    
    
    public static function roots($all = false)
    {
        if($all){
            return self::where('id', self::ACCOUNT_ASSETS)
            ->orwhere('id', self::ACCOUNT_LIABILITIES)
            ->orwhere('id', self::ACCOUNT_OWNERS)
            ->orwhere('id', self::ACCOUNT_EXPENSES)
            ->orwhere('id', self::ACCOUNT_REVENUES)
            ->orwhere('id', self::ACCOUNT_FINALS)
            ->orderBy('id')->get();
        }
        return self::where('id', self::ACCOUNT_ASSETS)
        ->orwhere('id', self::ACCOUNT_LIABILITIES)
        ->orwhere('id', self::ACCOUNT_OWNERS)
        ->orwhere('id', self::ACCOUNT_EXPENSES)
        ->orwhere('id', self::ACCOUNT_REVENUES)
        ->orderBy('id')->get();
        
    }
    
    public function isDefault(){
        return in_array($this->id, self::DEFAULTS);
    }
    
    public function isRoot(){
        return $this->id <= 6;
    }
    
    public function isPrimary(){
        return $this->type == self::TYPE_PRIMARY;
    }
    
    public function isSecondary(){
        return $this->type == self::TYPE_SECONDARY;
    }
    
    public function isDebt(){
        return $this->side == self::SIDE_DEBT;
    }
    
    public function isCredit(){
        return $this->side == self::SIDE_CREDIT;
    }
    
    public function isCashSafe(){
        $str = $this->id . '';
        if(strlen($str) > 4){
            return substr($str, 0, 4) == self::ACCOUNT_CASHES;
        }
        
        return false;
    }
    
    public function isBankSafe(){
        $str = $this->id . '';
        if(strlen($str) > 4){
            return substr($str, 0, 4) == self::ACCOUNT_BANKS;
        }
        
        return false;
    }
    public function hasParent($number){
        $str = $this->number . '';
        $num = $number . '';
        //        dd($num, $str);
        return substr($str, 0, strlen($num)) == $num;
    }
    public function isCustomer()
    {
        return $this->hasParent(self::ACCOUNT_CUSTOMERS);
    }
    public function isFinal(){
        $str = $this->id . '';
        if(strlen($str) > 1){
            return substr($str, 0, 1) == self::ACCOUNT_FINALS;
        }
        
        return false;
    }
    
    public function display(){
        return $this->number . '-' . $this->name;
    }
    
    public function getNumericNameAttribute(){
        return $this->number . '-' . $this->name;
    }
    
    public function getType(){
        return __('accounting::accounts.types.' . $this->type);
    }
    
    public function getSide(){
        return __('accounting::accounts.sides.' . $this->side);
    }
    
    
    public function debts($year_id = null){
        return $this->entries($year_id, Entry::SIDE_DEBTS);
    }
    
    
    public function credits($year_id = null){
        return $this->entries($year_id, Entry::SIDE_CREDITS);
    }
    
    public function entries($year_id = null, $side = null)
    {
        if($year_id){
            if($side){
                return $this->entries->where('year_id', $year_id)->where('pivot.side', $side);
            }
            return $this->entries->where('year_id', $year_id);
        }
        elseif($side){
            $year_id = $year_id ? $year_id : yearId();
            //            dd($year_id);
            return $this->entries->where('year_id', $year_id)->where('pivot.side', $side);
        }
        
        return $this->belongsToMany(Entry::class)->withPivot(['side', 'amount']);
    }
    
    public function currentEntries(){
        return $this->entries->where('year_id', yearId());
    }
    
    
    // public function balance($formatted = false, $year_id = null, $abstracted = false){
    //     $debts = $this->debts($year_id);
    //     $credits = $this->credits($year_id);
    //     // dd($debts->pluck('year_id', 'amount'), $credits->pluck('year_id', 'amount'));
    //     if($debts && $credits){
    //         $balance = $this->isDebt() ? $debts->sum('pivot.amount') - $credits->sum('pivot.amount') : $credits->sum('pivot.amount') - $debts->sum('pivot.amount');
    
    //         $balance = $abstracted ? abs($balance) : $balance;
    //         return $formatted ? number_format($balance, 2) : $balance;
    //     }
    //     return 0;
    // }
    
    public static function allToJson($with_children = false, $year_id = null){
        // $accounts = Account::all();
        // $striped = [];
        // foreach ($accounts as $account) {
        //     $striped[] = static::accountToArray($account, $with_children, $year_id);
        // }
        // return json_encode($striped);
        return Account::with('entries')->get()->toJson();
    }
    
    // public static function allToJson($all = false){
    //     return json_encode(static::allToArray($all));
    // }
    
    public static function allToArray($all = false, $with_children = false, $year_id = null){
        $roots = static::roots($all);
        $accounts = [];
        foreach (self::roots($all) as $child) {
            $accounts[] = static::accountToArray($child, $with_children, $year_id);
        }
        return $accounts;
    }
    
    public static function accountToArray($account, $with_children = false, $year_id = null){
        $data = $account->toArray();
        $entries = $account->entries($year_id);
        // dd($entries, $year_id);
        if($entries){
            $entries = $entries->toArray();
            // for ($i=0; $i < $account->entries->count(); $i++) {
            //     $year = $account->entries[$i]->year;
            //     if($year){
            //         $entries[$i]['year_id'] = $year->id;
            //     }
            // }
            $data['entries'] = $entries;
        }
        
        if($with_children){
            foreach ($account->children as $child) {
                $data['children'][] = static::accountToArray($child, $with_children, $year_id);
            }
        }
        
        return $data;
    }
    
    
    public function balance($formatted = false, $year_id = null, $abstracted = false){
        $debts = $this->debts($year_id);
        $credits = $this->credits($year_id);
        
        $debts_amount = $debts->sum('pivot.amount');
        $credits_amount = $credits->sum('pivot.amount');
        
        $balance = abs($debts_amount - $credits_amount);
        return $formatted ? number_format($balance, 2) : $balance;
    }
    
    public function balances($formatted = false, $year_id = null){
        $balances = $this->balance(false, $year_id);
        foreach ($this->children(true) as $child) {
            $balances += $child->balance(false, $year_id);
        }
        return $formatted ? number_format($balances, 2) : $balances;
    }
    
    // public function balance($year_id = null){
    //     $debts = $year_id ? $this->debtAmounts($year_id)->sum('amount') : $this->debtAmounts()->sum('amount');
    //     $credits = $year_id ? $this->creditAmounts($year_id)->sum('amount') : $this->creditAmounts()->sum('amount');
    //     // dd($debts, $credits);
    //     return new Balance($debts, $credits);
    // }
    public function amounts($year_id = null){
        $year_id = ($year_id) ? $year_id : (year() ? yearId() : null);
        // if($year_id){ return $this->hasMany(AccountEntry::class, 'account_id')->where('year_id', $year_id); }
        return $this->hasMany(AccountEntry::class, 'account_id')->where('year_id', $year_id);
    }
    
    public function debtAmounts($year_id = null){
        if($year_id){ return $this->amounts->where('year_id', $year_id)->where('type', AccountEntry::TYPE_DEBT); }
        return $this->amounts->where('type', AccountEntry::TYPE_DEBT);
    }
    public function creditAmounts($year_id = null){
        if($year_id){ return $this->amounts->where('year_id', $year_id)->where('type', AccountEntry::TYPE_CREDIT); }
        return $this->amounts->where('type', AccountEntry::TYPE_CREDIT);
    }
    
    
    public function final()
    {
        return  $this->belongsTo(Account::class, 'final_account');
    }
    
    public function costsCenters(){
        return $this->centers->where('type', Center::TYPE_COST);
    }
    
    public function profitsCenters(){
        return $this->centers->where('type', Center::TYPE_PROFIT);
    }
    
    public function centers()
    {
        return  $this->belongsToMany(Center::class, 'account_center');
    }
    
    
    public function parent()
    {
        return  $this->belongsTo(Account::class, 'main_account');
    }
    
    
    public function parents($fromRoot = false)
    {
        $parents = new Collection;
        $parent = $this->parent;
        while($parent){
            $parents->push($parent);
            $parent = $parent->parent;
        }
        return $fromRoot ? $parents->sortBy('number') : $parents;
    }
    
    
    public function order()
    {
        foreach ($this->children->sortBy('numer') as $index => $child) {
            $child->update(['number' => $this->number . ($index + 1)]);
        }
    }
    
    
    // public static function primaryAccounts($side = null)
    // {
    //     $accounts = self::where('type', self::TYPE_PRIMARY);
    //     if($side) $accounts = self::where('side', $side);
    //     return $accounts->orderBy('number')->get();
    // }
    
    
    public static function primaryAccounts($with_children = false, $side = null)
    {
        $accounts = self::where('type', self::TYPE_PRIMARY);
        if ($with_children) {
            $accounts->with('children');
        }
        if($side) $accounts->where('side', $side);
        return $accounts->orderBy('number')->get();
    }
    
    
    public static function secondaryAccounts($with_children = false, $side = null)
    {
        $accounts = self::where('type', self::TYPE_SECONDARY);
        if ($with_children) {
            $accounts->with('children');
        }
        if($side) $accounts->where('side', $side);
        return $accounts->orderBy('number')->get();
    }
    
    
    public static function rootsAccounts($all = false)
    {
        $extended = new Collection;
        foreach (self::roots($all) as $child) {
            $extended = $extended->merge(self::extendedAccounts($child));
        }
        return $extended;
    }
    
    
    public static function extendedAccounts($account)
    {
        $extended = new Collection;
        foreach ($account->children as $child) {
            $extended->push($child);
            $extended = $extended->merge(self::extendedAccounts($child));
        }
        return $extended;
    }
    
    
    public function children($nested = false, $onlyChildren = false)
    {
        if($nested){
            $children = new Collection();
            if(!$onlyChildren) $children->push($this);
            if($this->children->count()){
                foreach ($this->children as $child) {
                    $children = $children->merge($child->children($nested));
                }
            }
            
            return $children;
        }
        $fk = $this->isFinal() ? 'final_account' : 'main_account';
        $fk = 'main_account';
        return $this->hasMany(Account::class, $fk);
    }
    
    public function addChild($name, $type = null, $side = null){
        $attributes = [];
        $attributes['name'] = $name;
        $attributes['type'] = $type ? $type : self::TYPE_SECONDARY;
        $attributes['side'] = $side ? $side : $this->side;
        $attributes['main_account'] = $this->id;
        return self::create($attributes);
    }
    
    public function update(array $attributes = [], array $options = []){
        if(!$this->isRoot()){
            if(array_key_exists('main_account', $attributes) && !array_key_exists('number', $attributes)){
                if($this->main_account != $attributes['main_account']){
                    $old_parent = $this->parent;
                    $parent = Account::find($attributes['main_account']);
                    $attributes['number'] = $parent->number . ($parent->children->count() + 1);
                    
                    $result = parent::update($attributes, $options);
                    
                    if($old_parent) $old_parent->order();
                    
                    return $result;
                }else{
                    return parent::update($attributes, $options);
                }
            }
            else{
                return parent::update($attributes, $options);
            }
        }
        return null;
    }
    
    public static function create(array $attributes = []){
        // dd(request()->url() == route('accounts.store'));
        if(array_key_exists('main_account', $attributes) && (!array_key_exists('number', $attributes) || !array_key_exists('side', $attributes))){
            $parent = static::findOrFail($attributes['main_account']);
        }else {
            $parent = null;
        }
        
        if(array_key_exists('main_account', $attributes) && !array_key_exists('number', $attributes)){
            $attributes['number'] = $parent->number . ($parent->children->count() + 1);
        }
        if(array_key_exists('main_account', $attributes) && !array_key_exists('side', $attributes)){
            $attributes['side'] = $parent->side;
            // $attributes['side'] = $side ? $side : $parent->side;
        }

        if(array_key_exists('main_account', $attributes) && !array_key_exists('type', $attributes)){
            $attributes['type'] = Account::TYPE_SECONDARY;
        }
        $account = static::query()->create($attributes);
        $from_accounts_store_route = (request()->url() == route('accounts.store'));
        if ($from_accounts_store_route && $account->isSecondary()) {
            if ($account->hasParent(self::ACCOUNT_SAFES)) {
                $type = $account->hasParent(self::ACCOUNT_CASHES) ? Safe::TYPE_CASH : Safe::TYPE_BANK;
                $safe = Safe::create([
                'id' => $account->id,
                'name' => $account->name,
                'type' => $type,
                ]);
                $safe->account()->save($account);
            }
        }
        return $account;
    }
    
    public function delete(){
        if(!$this->isDefault()){
            $parent = $this->parent;
            $accountable = $this->accountable;
            foreach ($this->children as $child) {
                $child->delete();
            }
            $result =  parent::delete();
            if($parent) $parent->order();
            if($accountable) $accountable->delete();
            return $result;
        }
        
        return null;
    }
    
    public static function getChildren($account){
        
    }
    public static function getAll($all = true, $onlyChildren = false){
        $roots = self::roots($all);
        $children = new Collection();
        foreach ($roots as $root) {
            $children = $children->merge($root->children($all));
        }
        return $children;
    }
    public static function getEntryables($side = null){
        if($side){
            return Account::where('type', Account::TYPE_SECONDARY)
            ->where('sdie', $side)
            ->whereNotNull('main_account')
            ->orderBy('number')->get();
        }
        return Account::where('type', Account::TYPE_SECONDARY)->whereNotNull('main_account')->orderBy('number')->get();
    }
    
    public function backupData($year_id = null, $with_parent = true, $to_json = false, $json_format = JSON_PRETTY_PRINT){
        $data = $this->toArray();
        $entries = $this->entries->where('year_id', $year_id);
        // $year_id = is_null($year_id) ? yearId() : $year_id;
        if($with_parent && $this->parent){
            $data['parent'] = $this->parent->toArray();
        }
        if ($entries->count()) {
            $data['entries'] = $entries->map(function($entry){
                return $entry->backupData();
            });
        }
        $children = [];
        foreach ($this->children as $child) {
            $children[] = $child->backupData($year_id, false);
        }
        if(count($children)){
            $data['children'] = $children;
        }
        
        return $data;
    }
}