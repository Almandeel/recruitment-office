<?php
namespace Modules\Accounting\Traits;
use Modules\Accounting\Models\Account;
use Modules\Employee\Models\Employee;
use Modules\Accounting\Models\Safe;
use Modules\Services\Models\Customer;
use Modules\Main\Models\Office;
/**
*  Accountable Trait
*/
trait Accountable
{


    public function getِAccountName(){
        return $this->name;
    }

    public function account()
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    public function createAccount($parent = null, $type = null, $side = null){
        if(is_null($parent)){
            $this_class = get_class($this);
            switch ($this_class) {
                case Safe::class:
                    $parent = ($this->type == $this_class::TYPE_CASH) ? Account::cashes() : Account::banks();
                    break;

                case Customer::class:
                    $parent = Account::customers();
                    break;

                case Employee::class:
                    $parent = Account::employees();
                    break;

                case Office::class:
                    $parent = Account::externalOffices();
                    break;
        }
    }
    $attributes = [];
    $attributes['name'] = $this->getِAccountName();
    $attributes['type'] = $type ? $type : Account::TYPE_SECONDARY;
    $attributes['side'] = $side ? $side : $parent->side;
    $attributes['main_account'] = $parent->id;
    // $attributes['accountable_id'] = $this->id;
    // $attributes['accountable_type'] = get_class($this);
    $account = Account::create($attributes);
    $this->id = $account->id;
    return $account;
}

public function balance($formatted = false, $year_id = null, $abstracted = false)
{
    return $this->account->balance($formatted, $year_id, $abstracted);
}

public function debts($year_id = null){
    return $this->account->debts($year_id);
}


public function credits($year_id = null){
    return $this->account->credits($year_id);
}

public function entries($year_id = null, $side = null)
{
    return $this->account->entries($year_id, $side);
}

public static function bootAccountable(){
    static::saved(function($model){
        if (!auth()->check()) {
            return;
        }

        $dirty = $model->getDirty();

        if (count($dirty) > 0)
        {
            if($model->wasRecentlyCreated){
                // $from_stores_route = (request()->url() == route('stores.store'));
                // if($from_stores_route){
                // if(is_null($model->id) && is_null($model->account_id)){
                if(is_null($model->account)){
                    $account = Account::find($model->id);
                    if(is_null($account)){
                        $account = $model->createAccount();
                        $model->save();
                    }
                    $model->account()->save($account);
                }
            }else{
                if(isset($model->account)){
                    // $from_accounts_update_route = (request()->url() == route('accounts.update', $model->account));
                    // if($from_accounts_update_route){
                    //     $model->account->update(['name' => $model->name]);
                    // }
                    $model->account->update(['name' => $model->name]);
                }
            }
        }
    });

    static::creating(function($model){
        if (auth()->check()) {
            return;
        }

        if(is_null($model->id)){
            $model->createAccount();
        }
    });

    // static::updated(function($model){
    //     $model->account->update(['name' => $model->name]);
    // });
    static::deleted(function($model){
        // if($from_accounts_update_route){
        //     $model->account->update(['name' => $model->name]);
        // }
        if($model->account) {
            $from_accounts_destroy_route = (request()->url() == route('accounts.destroy', $model->account));
            if(!$from_accounts_destroy_route){
                $model->account->delete();
            }
        }
    });
}
}
