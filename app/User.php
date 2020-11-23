<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Modules\Employee\Models\Employee;
use Modules\Mail\Models\Letter;
use Modules\Accounting\Models\Expense;
use Modules\Accounting\Models\Custody;
use App\Traits\Logable;
class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    use Logable;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $table = 'users';
    protected $fillable = [
    'name',	'username',	'password',	'employee_id'
    ];
    
    
    public function authable()
    {
        return $this->morphedByMany(__FUNCTION__, 'authable_type', 'authable_id');
    }
    
    public function entries()
    {
        return $this->morphedByMany(Entry::class, 'authable');
    }
    
    public function vouchers()
    {
        return $this->morphedByMany(Vouchr::class, 'authable');
    }
    public function expenses()
    {
        return $this->morphedByMany(Expense::class, 'authable');
    }
    public function transfers()
    {
        return $this->morphedByMany(Transfer::class, 'authable');
    }
    public function logs()
    {
        return $this->morphedByMany(Log::class, 'authable');
    }
    
    
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
    'password', 'remember_token',
    ];
    
    /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
    
    public function letters()
    {
        return $this->belongsToMany(Letter::class)->withPivot(['box', 'status']);
    }
    
    public function getBox($box = 1, $status = 1){
        return $this->letters->filter(function($letter) use($box, $status){ return $letter->pivot->box == $box && $letter->pivot->status == $status; });
    }
    
    public function inbox(){
        return $this->getBox(Letter::BOX_INBOX);
    }
    
    public function outbox(){
        return $this->getBox(Letter::BOX_OUTBOX);
    }
    
    public function drafts(){
        return $this->getBox(Letter::BOX_DRAFTS);
    }
    
    public function trashbox(){
        return $this->letters->filter(function($letter){ return $letter->pivot->status == 0; });
    }
    
    
    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
    
    public function custodies()
    {
        return $this->hasMany(Custody::class, 'employee_id', 'employee_id');
    }
    
    
    public function transactions(){
        return $this->hasMany('App\Transaction');
    }
    
    public function isSuper(){
        return $this->hasRole("superadmin|super");
    }
    
    public static function super(){
        return self::where("name", "superadmin")->orwhere("name", "super")->get()->first();
    }
    
    public function warehouses(){
        return $this->belongsToMany('Modules\Warehouse\Models\Warehouse');
    }
    public function getClient(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        
    }
    
    public static function create(array $attributes = [])
    {
        if(array_key_exists('employee_id', $attributes) && !array_key_exists('name', $attributes)){
            $attributes['name'] = Employee::findOrFail($attributes['employee_id'])->name;
        }{
            if($attributes['name'] == null){
                $attributes['name'] = Employee::findOrFail($attributes['employee_id'])->name;
            }
        }
        $model = static::query()->create($attributes);
        return $model;
    }
    
    public function update(array $attributes = [], array $options = []){
        if(array_key_exists('employee_id', $attributes) && !array_key_exists('name', $attributes)){
            $attributes['name'] = Employee::findOrFail($attributes['employee_id'])->name;
        }
        return parent::update($attributes, $options);
    }
    
    public function delete(){
        $result =  parent::delete();
        return $result;
    }
    
    
}